import React from "react";
import { GluestackUIProvider } from "@/components/ui/gluestack-ui-provider";
import { AuthConsumer, AuthProvider } from "../contexts/auth";
import { Slot } from "expo-router";

export default function Root() {
  return (
    <GluestackUIProvider mode="light">
      <AuthProvider>
        <AuthConsumer>
          <Slot />
        </AuthConsumer>
      </AuthProvider>
    </GluestackUIProvider>
  );
}
