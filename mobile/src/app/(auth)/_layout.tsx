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
    <Stack>
      <Stack.Screen name="(auth)" options={{ headerShown: false }} />
    </Stack>
  );
}
