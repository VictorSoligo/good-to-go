import { Box } from "@/components/ui/box";
import { Spinner } from "@/components/ui/spinner";
import { VStack } from "@/components/ui/vstack";
import React from "react";

export function Loading() {
  return (
    <Box className="flex-1 justify-center items-center">
      <Spinner size="large" />
    </Box>
  );
}
