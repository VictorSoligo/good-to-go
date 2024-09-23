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
import { StoreRepository } from "@/src/repositories/store-repository";
import { useQuery } from "@tanstack/react-query";
import { Stack } from "expo-router";
import { FlatList, TouchableOpacity } from "react-native";

export default function Home() {
  const { account, logout } = useAuthContext();

  const { data: stores = [] } = useQuery({
    queryKey: ["stores"],
    queryFn: StoreRepository.getStores,
  });

  return (
    <Container>
      <Stack.Screen
        options={{
          headerShown: false,
        }}
      />
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

        <TouchableOpacity activeOpacity={0.7}>
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

        <VStack>
          <Text className="font-bold text-xl text-primary-700">
            Lojas Destaques
          </Text>

          <FlatList
            data={stores}
            horizontal
            renderItem={({ item }) => {
              return (
                <Card className="items-center">
                  <Image
                    source={{
                      uri: HOST_API + "/attachments/" + item.attachment.url,
                    }}
                    alt="Imagem da loja"
                    className="w-32 h-32 rounded-full"
                  />
                  <Text className="text-sm font-bold text-primary-600 mt-2">
                    {item.name}
                  </Text>
                </Card>
              );
            }}
          />
        </VStack>
      </VStack>
    </Container>
  );
}
