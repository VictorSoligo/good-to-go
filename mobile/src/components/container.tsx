import { Box } from "@/components/ui/box";
import { PropsWithChildren } from "react";
import { useSafeAreaInsets } from "react-native-safe-area-context";

type Props = PropsWithChildren & {
  hasHeader?: boolean;
};

export function Container({ children, hasHeader = false }: Props) {
  const insets = useSafeAreaInsets();

  return (
    <Box
      className="flex-1 px-6 bg-white"
      style={{
        paddingTop: hasHeader ? 16 : insets.top,
        paddingBottom: insets.bottom,
      }}
    >
      {children}
    </Box>
  );
}
