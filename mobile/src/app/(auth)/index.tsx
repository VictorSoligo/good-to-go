import { Card } from "@/components/ui/card";
import { HStack } from "@/components/ui/hstack";
import { ChevronRightIcon, Icon } from "@/components/ui/icon";
import { Image } from "@/components/ui/image";
import { Text } from "@/components/ui/text";
import { VStack } from "@/components/ui/vstack";
import { Button } from "@/src/components/button";
import { Container } from "@/src/components/container";
import { HOST_API } from "@/src/config-global";
import { useAuthContext } from "@/src/hooks/use-auth-context";
import { OfferRepository } from "@/src/repositories/offer-repository";
import { StoreRepository } from "@/src/repositories/store-repository";
import { useQuery } from "@tanstack/react-query";
import { router, Stack } from "expo-router";
import { MapPin } from "lucide-react-native";
import { FlatList, ScrollView, TouchableOpacity } from "react-native";

export default function Home() {
  const { account, logout } = useAuthContext();

  const { data: stores = [] } = useQuery({
    queryKey: ["stores"],
    queryFn: StoreRepository.getStores,
  });

  const { data: offers = [] } = useQuery({
    queryKey: ["offers"],
    queryFn: OfferRepository.getOffers,
  });

  return (
    <Container>
      <Stack.Screen
        options={{
          headerShown: false,
        }}
      />
      <ScrollView
        showsVerticalScrollIndicator={false}
        contentContainerClassName="pb-10"
      >
        <VStack space="2xl">
          <HStack className="items-center ">
            <VStack className="flex-1">
              <Text className="text-4xl font-bold color-primary-main">
                Good to go
              </Text>
              <Text className="text-2xl">Olá {account?.name}! 😄</Text>
            </VStack>

            <Button text="Sair" variant="link" size="md" onPress={logout} />
          </HStack>

          {account?.role === "manager" && (
            <TouchableOpacity
              activeOpacity={0.7}
              onPress={() => {
                router.push({
                  pathname: "/new-store",
                });
              }}
            >
              <VStack className="px-4 py-2 rounded-md bg-primary-main">
                <Text className="text-2xl font-bold text-white">
                  Cadastrar uma loja
                </Text>
                <Text className="text-white font-bold">
                  Crie sua loja e comece a vender seus produtos na internet
                </Text>

                <VStack className="items-end self-end mt-3 bg-white rounded-full p-2">
                  <Icon as={ChevronRightIcon} />
                </VStack>
              </VStack>
            </TouchableOpacity>
          )}

          <VStack>
            <Text className="font-bold text-xl text-primary-700">
              Produtos do Dia
            </Text>

            <FlatList
              data={offers}
              horizontal
              showsHorizontalScrollIndicator={false}
              renderItem={({ item }) => {
                return (
                  <TouchableOpacity
                    onPress={() => {
                      router.push({
                        pathname: "/offer",
                        params: {
                          offerId: item.id,
                        },
                      });
                    }}
                  >
                    <Card className="px-3">
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
                      <VStack className="px-1 mt-2">
                        <Text
                          className="text-lg font-bold text-primary-400 "
                          numberOfLines={1}
                        >
                          {item.productName}
                        </Text>

                        <HStack
                          className="justify-between items-center"
                          space="xs"
                        >
                          <HStack className="items-center flex-1">
                            <MapPin color="#2E7D32" size={16} />
                            <Text numberOfLines={1}>{item.store.name}</Text>
                          </HStack>

                          <Text
                            numberOfLines={1}
                            className="text-md font-bold text-primary-600"
                          >
                            $ {(item.price / 100).toFixed(2)}
                          </Text>
                        </HStack>
                      </VStack>
                    </Card>
                  </TouchableOpacity>
                );
              }}
            />
          </VStack>

          <VStack>
            <Text className="font-bold text-xl text-primary-700">
              Lojas Destaques
            </Text>

            <FlatList
              data={stores}
              horizontal
              showsHorizontalScrollIndicator={false}
              renderItem={({ item }) => {
                return (
                  <TouchableOpacity
                    activeOpacity={0.7}
                    onPress={() => {
                      router.push({
                        pathname: "/shop",
                        params: {
                          shopId: item.id,
                        },
                      });
                    }}
                  >
                    <Card className="items-center">
                      <Image
                        source={{
                          uri: HOST_API + "/attachments/" + item.attachment.url,
                        }}
                        alt="Imagem da loja"
                        className="w-28 h-28 rounded-full"
                      />
                      <Text className="text-sm font-bold text-primary-600 mt-2">
                        {item.name}
                      </Text>
                    </Card>
                  </TouchableOpacity>
                );
              }}
            />
          </VStack>
        </VStack>
      </ScrollView>
    </Container>
  );
}
