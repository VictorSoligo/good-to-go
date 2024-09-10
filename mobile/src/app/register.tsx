import { Container } from "../components/container";
import { Text, View } from "react-native";

export default function Page() {
  return (
    <Container hasHeader>
      <View className="items-center">
        <Text className="text-4xl font-bold color-primary-main">
          Good to go
        </Text>
        <Text className="text-2xl">Bem vindo 😄</Text>
      </View>

      <View></View>
    </Container>
  );
}
