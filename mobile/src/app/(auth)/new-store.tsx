import { VStack } from "@/components/ui/vstack";
import FormProvider from "@/src/components/hook-form/form-provider";
import { RHFTextField } from "@/src/components/hook-form/rhf-text-field";
import { YupCreateStoreSchema } from "@/src/schemas/store/new";
import { yupResolver } from "@hookform/resolvers/yup";
import { router, Stack } from "expo-router";
import { useState } from "react";
import { useForm } from "react-hook-form";
import { ScrollView, Text, View } from "react-native";
import * as ImagePicker from "expo-image-picker";
import RHFLibrary from "@/src/components/hook-form/rhf-library";
import { Button } from "@/src/components/button";
import { StorageRepository } from "@/src/repositories/storage-repository";
import { useMutation, useQueryClient } from "@tanstack/react-query";
import { StoreRepository } from "@/src/repositories/store-repository";

type FormDataProps = {
  name: string;
  adress: string;
  image: ImagePicker.ImagePickerAsset;
};

export default function Page() {
  const [errorText, setErrorText] = useState<string>("");

  const queryClient = useQueryClient();

  const { mutateAsync, isPending } = useMutation({
    mutationFn: StoreRepository.createStore,
  });

  const methods = useForm<FormDataProps>({
    // @ts-ignore
    resolver: yupResolver(YupCreateStoreSchema),
  });

  const { handleSubmit } = methods;

  const onSubmit = handleSubmit(async ({ adress, image, name }) => {
    try {
      const formData = new FormData();
      // @ts-ignore
      formData.append("file", {
        uri: image.uri,
        type: image.type,
        name: image.fileName || `${name}.jpg`,
      });

      const { id } = await StorageRepository.uploadImage(formData);

      await mutateAsync({
        adress,
        attachmentId: id,
        name,
      });

      queryClient.invalidateQueries({ queryKey: ["stores"] });

      router.replace("/");
    } catch (error: any) {
      const title =
        "Não foi possível acessar conta.\nTente novamente mais  tarde";

      setErrorText(error?.response?.data?.message ?? title);
    }
  });

  return (
    <VStack className="flex-1 px-6 bg-white py-4 pb-10" space="md">
      <Stack.Screen
        options={{
          title: "Nova loja",
        }}
      />

      <ScrollView showsVerticalScrollIndicator={false}>
        <Text className="text-2xl font-bold text-center">
          Cadastrar uma loja
        </Text>

        <Text className="text-lg text-center">
          Preencha os campos abaixo para cadastrar uma nova loja.
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
              name="name"
              label="Nome"
              inputProps={{
                placeholder: "Nome da loja",
              }}
            />

            <RHFTextField
              name="adress"
              label="Endereço"
              inputProps={{
                placeholder: "Endereço da loja",
              }}
            />
          </View>
        </FormProvider>
      </ScrollView>

      <Button text="Cadastrar" onPress={onSubmit} isLoading={isPending} />
    </VStack>
  );
}
