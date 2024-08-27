import React from "react";
import { StyleSheet, Text, View } from "react-native";
import { Container } from "../components/container";

export default function Page() {
  return (
    <Container>
      <View className="items-center p-6">
        <Text className="text-6xl font-bold color-primary-50">Good to go</Text>
        <Text className="text-4xl">Bem vindo de volta! 😄</Text>
      </View>
    </Container>
  );
}
