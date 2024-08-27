import { Box } from "@/components/ui/box";
import { PropsWithChildren } from "react";
import { useSafeAreaInsets } from "react-native-safe-area-context";

type Props = PropsWithChildren & {};

export function Container({ children }: Props) {
  const insets = useSafeAreaInsets();

  return (
    <Box
      className="flex-1"
      style={{
        paddingTop: insets.top,
        paddingBottom: insets.bottom,
      }}
    >
      {children}
    </Box>
  );
}
