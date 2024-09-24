import {
  ButtonIcon,
  ButtonSpinner,
  ButtonText,
  Button as GlueButton,
} from "@/components/ui/button";
import { ComponentProps } from "react";

type Props = ComponentProps<typeof GlueButton> & {
  text: string;
  leftIcon?: any;
  rightIcon?: any;
  isLoading?: boolean;
  buttonTextProps?: ComponentProps<typeof ButtonText>;
};

export function Button({
  text,
  leftIcon,
  rightIcon,
  isLoading = false,
  buttonTextProps,
  ...rest
}: Props) {
  return (
    <GlueButton
      size="xl"
      variant="solid"
      className="gap-1 rounded-md"
      disabled={isLoading}
      {...rest}
    >
      {leftIcon && !isLoading && <ButtonIcon as={leftIcon} />}

      {isLoading && <ButtonSpinner className="color-white" />}

      <ButtonText className="font-bold" {...buttonTextProps}>
        {text}
      </ButtonText>

      {rightIcon && <ButtonIcon as={rightIcon} />}
    </GlueButton>
  );
}
