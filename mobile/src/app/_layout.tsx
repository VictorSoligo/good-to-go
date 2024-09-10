import React from "react";
import { GluestackUIProvider } from "@/components/ui/gluestack-ui-provider";
import { AuthConsumer, AuthProvider } from "../contexts/auth";
import { Stack } from "expo-router";

// Import your global CSS file
import "../../global.css";

export default function Root() {
  return (
    <GluestackUIProvider mode="light">
      <AuthProvider>
        <AuthConsumer>
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
              name="register"
              options={{
                title: "Cadastrar",
              }}
            />
          </Stack>
        </AuthConsumer>
      </AuthProvider>
    </GluestackUIProvider>
  );
}
