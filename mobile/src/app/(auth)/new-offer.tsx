import { VStack } from "@/components/ui/vstack";
import { Button } from "@/src/components/button";
import FormProvider from "@/src/components/hook-form/form-provider";
import RHFLibrary from "@/src/components/hook-form/rhf-library";
import { RHFTextField } from "@/src/components/hook-form/rhf-text-field";
import { Loading } from "@/src/components/loading";
import { useAuthContext } from "@/src/hooks/use-auth-context";
import { OfferRepository } from "@/src/repositories/offer-repository";
import { StorageRepository } from "@/src/repositories/storage-repository";
import { StoreRepository } from "@/src/repositories/store-repository";
import { YupCreateOfferSchema } from "@/src/schemas/offer/new";
import { yupResolver } from "@hookform/resolvers/yup";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { ImagePickerAsset } from "expo-image-picker";
import { Redirect, router, Stack, useLocalSearchParams } from "expo-router";
import { useState } from "react";
import { useForm } from "react-hook-form";
import { ScrollView, Text, View } from "react-native";

type FormDataProps = {
  productName: string;
  description: string;
  price: number;
  availableUntil: string;
  image: ImagePickerAsset;
};

export default function Page() {
  const { account } = useAuthContext();
  const [errorText, setErrorText] = useState<string>("");

  const { shopId } = useLocalSearchParams<{ shopId: string }>();

  const queryClient = useQueryClient();

  const {
    data: shop,
    isLoading,
    isError,
  } = useQuery({
    queryKey: ["shop", shopId],
    queryFn: () => StoreRepository.getStoreById(shopId),
  });

  const { mutateAsync, isPending } = useMutation({
    mutationFn: OfferRepository.createOffer,
  });

  const methods = useForm<FormDataProps>({
    // @ts-ignore
    resolver: yupResolver(YupCreateOfferSchema),
  });

  const { handleSubmit } = methods;

  const onSubmit = handleSubmit(
    async ({ availableUntil, description, image, price, productName }) => {
      try {
        const formData = new FormData();
        // @ts-ignore
        formData.append("file", {
          uri: image.uri,
          type: image.type,
          name: image.fileName || `${description}.jpg`,
        });

        const { id } = await StorageRepository.uploadImage(formData);

        const [day, month, year] = availableUntil.split("/");
        const formattedDate = `${year}-${month}-${day}`;

        await mutateAsync({
          availableUntil: formattedDate,
          description,
          productName,
          attachmentsIds: [id],
          price: Number(price) * 100,
          storeId: shopId,
        });

        queryClient.invalidateQueries({ queryKey: ["offers"] });

        router.replace("/");
      } catch (error: any) {
        const title =
          "Não foi possível acessar conta.\nTente novamente mais tarde";

        setErrorText(error?.response?.data?.message ?? title);
      }
    }
  );

  if (isLoading) {
    return <Loading />;
  }

  if (isError || shop?.ownerId !== account?.id) {
    return <Redirect href="/" />;
  }

  return (
    <VStack className="flex-1 px-6 bg-white py-4 pb-10" space="md">
      <Stack.Screen
        options={{
          title: `Nova Oferta da ${shop?.name}`,
        }}
      />

      <ScrollView showsVerticalScrollIndicator={false}>
        <Text className="text-2xl font-bold text-center">
          Oferta para {shop?.name}
        </Text>

        <Text className="text-lg text-center">
          Preencha os campos abaixo para cadastrar uma nova oferta.
        </Text>

        <FormProvider methods={methods}>
          <View className="mt-6">
            {errorText && (
              <View className="flex-row bg-error-50 p-3 mb-5 rounded-md">
                <Text className="font-bold text-error-600">{errorText}</Text>
              </View>
            )}

            <View className="h-44 w-full mb-2">
              <RHFLibrary name="image" label="Imagem" />
            </View>

            <RHFTextField
              name="productName"
              label="Nome Produto"
              inputProps={{
                placeholder: "Nome do Produto",
              }}
            />

            <RHFTextField
              name="description"
              label="Descrição"
              sexo={{
                className: "h-24",
              }}
              inputProps={{
                multiline: true,
                placeholder: "Descrição da oferta",
              }}
            />

            <RHFTextField
              name="price"
              label="Preço"
              inputProps={{
                placeholder: "Preço da oferta",
              }}
            />

            <RHFTextField
              name="availableUntil"
              label="Valido até"
              inputProps={{
                placeholder: "Valido até",
              }}
            />
          </View>
        </FormProvider>
      </ScrollView>

      <Button text="Cadastrar" onPress={onSubmit} isLoading={isPending} />
    </VStack>
  );
}
