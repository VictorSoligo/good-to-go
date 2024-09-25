import React from "react";
import { Redirect, Slot, Stack } from "expo-router";
import { useAuthContext } from "@/src/hooks/use-auth-context";
import { Loading } from "@/src/components/loading";

export default function AppLayout() {
  const { loading, unauthenticated, account } = useAuthContext();

  if (loading) {
    return <Loading />;
  }

  if (unauthenticated) {
    return <Redirect href="/sign-in" />;
  }

  return (
    <Stack
      screenOptions={{
        headerTintColor: "#2E7D32",
        headerTitleStyle: {
          fontWeight: "bold",
          color: "#000",
        },
      }}
    >
      <Stack.Screen
        name="index"
        options={{ headerShown: false, title: "Início" }}
      />

      <Stack.Screen name="shop" />

      <Stack.Screen name="offer" />

      <Stack.Screen name="new-store" />

      <Stack.Screen name="new-offer" />
    </Stack>
  );
}
