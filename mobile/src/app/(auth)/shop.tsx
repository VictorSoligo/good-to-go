import { Card } from "@/components/ui/card";
import { HStack } from "@/components/ui/hstack";
import { Text } from "@/components/ui/text";
import { VStack } from "@/components/ui/vstack";
import { Button } from "@/src/components/button";
import { Loading } from "@/src/components/loading";
import { HOST_API } from "@/src/config-global";
import { useAuthContext } from "@/src/hooks/use-auth-context";
import { OfferRepository } from "@/src/repositories/offer-repository";
import { StoreRepository } from "@/src/repositories/store-repository";
import { useQuery, useQueryClient } from "@tanstack/react-query";
import { Redirect, router, Stack, useLocalSearchParams } from "expo-router";
import { Clock, MapPin } from "lucide-react-native";
import { Dimensions, Image, ScrollView, TouchableOpacity } from "react-native";

export default function Shop() {
  const { account } = useAuthContext();

  const queryClient = useQueryClient();

  const { shopId } = useLocalSearchParams<{ shopId: string }>();

  const {
    data: shop,
    isLoading,
    isError,
  } = useQuery({
    queryKey: ["shop", shopId],
    queryFn: () => StoreRepository.getStoreById(shopId),
  });

  const { data: offers = [], refetch: re } = useQuery({
    queryKey: ["offers"],
    queryFn: OfferRepository.getOffers,
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
          title: shop?.name,
        }}
      />

      <Image
        source={{
          uri: HOST_API + "/attachments/" + shop?.attachment.url,
        }}
        style={{ width: "100%", height: Dimensions.get("window").height / 4.5 }}
      />

      <VStack className="flex-1 px-6 bg-white py-4 pb-10" space="md">
        <ScrollView showsVerticalScrollIndicator={false}>
          <HStack className="justify-between items-center">
            <Text className="text-2xl font-bold">{shop?.name}</Text>

            <Text className="text-md">
              Ofertas Hoje{" "}
              {offers.filter((item) => item.store.id === shopId).length}
            </Text>
          </HStack>

          <HStack className="p-3 bg-gray-100 rounded-md my-4" space="md">
            <MapPin color="#2E7D32" size={16} />

            <Text>{shop?.adress}</Text>
          </HStack>

          <VStack className="mt-8">
            <Text className="font-bold text-xl text-primary-700">
              Outros Produtos
            </Text>

            {offers.filter((item) => item.store.id === shopId).length === 0 && (
              <Text className="text-center text-lg mt-4">
                Nenhuma oferta disponível
              </Text>
            )}

            {offers
              .filter((item) => item.store.id === shopId)
              .map((item) => {
                return (
                  <TouchableOpacity
                    key={item.id}
                    onPress={() => {
                      router.push({
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

        {account?.id === shop?.ownerId && (
          <Button
            text="Cadastrar Oferta"
            action="primary"
            onPress={() => {
              router.push({
                pathname: "/new-offer",
                params: {
                  shopId,
                },
              });
            }}
          />
        )}
      </VStack>
    </VStack>
  );
}
