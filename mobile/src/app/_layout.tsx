import React from "react";
import { GluestackUIProvider } from "@/components/ui/gluestack-ui-provider";
import { AuthProvider } from "../contexts/auth";
import { Stack } from "expo-router";

// Import your global CSS file
import "../../global.css";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";

export const unstable_settings = {
  initialRouteName: "sign-in",
};

export default function Root() {
  return (
    <QueryClientProvider client={new QueryClient()}>
      <GluestackUIProvider mode="light">
        <AuthProvider>
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
              name="sign-in"
              options={{
                title: "Entrar",
              }}
            />

            <Stack.Screen
              name="register"
              options={{
                title: "Cadastrar",
              }}
            />

            <Stack.Screen
              name="(auth)"
              options={{
                headerShown: false,
              }}
            />
          </Stack>
        </AuthProvider>
      </GluestackUIProvider>
    </QueryClientProvider>
  );
}
