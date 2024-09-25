import { BadgeIcon, BadgeText, Badge } from "@/components/ui/badge";
import { Card } from "@/components/ui/card";
import { HStack } from "@/components/ui/hstack";
import { VStack } from "@/components/ui/vstack";
import { Button } from "@/src/components/button";
import { Loading } from "@/src/components/loading";
import { HOST_API } from "@/src/config-global";
import { useAuthContext } from "@/src/hooks/use-auth-context";
import { OfferRepository } from "@/src/repositories/offer-repository";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { Redirect, router, Stack, useLocalSearchParams } from "expo-router";
import { Clock, HouseIcon, MapPin } from "lucide-react-native";
import {
  Dimensions,
  Image,
  ScrollView,
  Text,
  TouchableOpacity,
} from "react-native";

export default function Offer() {
  const { account } = useAuthContext();

  const queryClient = useQueryClient();

  const { offerId } = useLocalSearchParams<{ offerId: string }>();

  const {
    data: offer,
    refetch,
    isLoading,
    isError,
  } = useQuery({
    queryKey: ["offer", offerId],
    queryFn: () => OfferRepository.getOfferById(offerId),
  });

  const { data: offers = [], refetch: re } = useQuery({
    queryKey: ["offers"],
    queryFn: OfferRepository.getOffers,
  });

  const { mutate, isPending } = useMutation({
    mutationFn: OfferRepository.cancelOffer,
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["offer", offerId] });
      queryClient.invalidateQueries({ queryKey: ["offers"] });
      re();
      refetch();
    },
  });

  if (isLoading) {
    return <Loading />;
  }

  if (isError) {
    return <Redirect href="/" />;
  }

  return (
    <VStack className="flex-1">
      <Stack.Screen
        options={{
          title: offer?.productName || "Oferta",
        }}
      />

      <Image
        source={{
          uri: HOST_API + "/attachments/" + offer?.attachments[0].url,
        }}
        style={{ width: "100%", height: Dimensions.get("window").height / 4.5 }}
      />

      <VStack className="flex-1 px-6 bg-white py-4 pb-10" space="md">
        <ScrollView showsVerticalScrollIndicator={false}>
          <HStack className="justify-between items-center">
            <Text className="text-xl font-bold">{offer?.productName}</Text>

            <Text className="text-3xl font-bold">
              $ {(offer?.price! / 100).toFixed(2)}
            </Text>
          </HStack>

          <HStack className="p-3 bg-gray-100 rounded-md my-4" space="md">
            <MapPin color="#2E7D32" size={16} />

            <Text numberOfLines={1}>{offer?.store.name}</Text>

            <Text>{offer?.store?.address}</Text>
          </HStack>

          <HStack className="items-center justify-between">
            <Badge size="lg" action={offer?.canceledAt ? "error" : "success"}>
              <BadgeIcon as={HouseIcon} />

              <BadgeText className="ml-2">retirada</BadgeText>
            </Badge>

            <HStack space="xs" className="items-center">
              {offer?.canceledAt ? (
                <>
                  <Clock color="red" size={16} />
                  <Text>Oferta cancelada</Text>
                </>
              ) : (
                <>
                  <Clock color="#2E7D32" size={16} />
                  <Text>
                    Válida até{" "}
                    {new Date(
                      offer?.availableUntil ?? new Date()
                    ).toLocaleDateString()}
                  </Text>
                </>
              )}
            </HStack>
          </HStack>

          <VStack className="mt-8">
            <Text className="font-bold text-xl text-primary-700">
              Descrição
            </Text>

            <Text className="text-lg mx-2">{offer?.description}</Text>
          </VStack>

          <VStack className="mt-8">
            <Text className="font-bold text-xl text-primary-700">
              Outros Produtos
            </Text>

            {offers
              .filter((o) => o.id !== offerId)
              .map((item) => {
                return (
                  <TouchableOpacity
                    key={item.id}
                    onPress={() => {
                      router.replace({
                        pathname: "/offer",
                        params: {
                          offerId: item.id,
                        },
                      });
                    }}
                  >
                    <Card className="px-3 flex-row">
                      <Image
                        source={{
                          uri:
                            HOST_API +
                            "/attachments/" +
                            item.attachments[0].url,
                        }}
                        alt="Imagem da loja"
                        className="w-40 h-40 rounded-md"
                      />

                      <VStack className="px-3 mt-2 justify-center" space="md">
                        <Text
                          className="text-2xl font-bold text-primary-700 "
                          numberOfLines={1}
                        >
                          {item.productName}
                        </Text>

                        <HStack className="items-center">
                          <MapPin color="#2E7D32" size={16} />
                          <Text numberOfLines={1}>{item.store.name}</Text>
                        </HStack>

                        <HStack className="items-center justify-between">
                          <HStack space="xs" className="items-center">
                            {item?.canceledAt ? (
                              <>
                                <Clock color="red" size={16} />
                                <Text>Oferta cancelada</Text>
                              </>
                            ) : (
                              <>
                                <Clock color="#2E7D32" size={16} />
                                <Text>
                                  Válida até{" "}
                                  {new Date(
                                    item?.availableUntil ?? new Date()
                                  ).toLocaleDateString()}
                                </Text>
                              </>
                            )}
                          </HStack>
                        </HStack>

                        <Text
                          numberOfLines={1}
                          className="text-xl font-bold text-primary-600"
                        >
                          $ {(item.price / 100).toFixed(2)}
                        </Text>
                      </VStack>
                    </Card>
                  </TouchableOpacity>
                );
              })}
          </VStack>
        </ScrollView>

        {account?.id === offer?.store.ownerId && (
          <Button
            text="Cancelar Oferta"
            action="negative"
            isLoading={isPending}
            onPress={() => {
              mutate(offerId);
            }}
          />
        )}
      </VStack>
    </VStack>
  );
}
