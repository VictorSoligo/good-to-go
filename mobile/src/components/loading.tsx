import { Box } from "@/components/ui/box";
import { Spinner } from "@/components/ui/spinner";
import React from "react";
import { Container } from "./container";

export function Loading() {
  return (
    <Container>
      <Box className="flex-1 justify-center items-center">
        <Spinner size="large" />
      </Box>
    </Container>
  );
}
