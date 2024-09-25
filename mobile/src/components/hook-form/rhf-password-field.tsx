import {
  FormControl,
  FormControlError,
  FormControlErrorIcon,
  FormControlErrorText,
  FormControlHelper,
  FormControlHelperText,
  FormControlLabel,
  FormControlLabelText,
} from "@/components/ui/form-control";
import { AlertCircleIcon, EyeIcon, EyeOffIcon } from "@/components/ui/icon";
import { Input, InputField, InputIcon, InputSlot } from "@/components/ui/input";
import { ComponentProps, useState } from "react";
// Ui
// RHF
import { Controller, useFormContext } from "react-hook-form";

// ----------------------------------------------------------------

type Props = {
  name: string;
  label?: string;
  helperText?: string;
  inputProps?: ComponentProps<typeof InputField>;
  formControl?: ComponentProps<typeof FormControl>;
};

export default function RHFPasswordField({
  name,
  label,
  helperText,
  inputProps,
  formControl,
}: Props) {
  const [isPasswordVisible, setIsPasswordVisible] = useState(false);

  const { control } = useFormContext();

  function handleState() {
    setIsPasswordVisible((prev) => !prev);
  }

  return (
    <Controller
      name={name}
      control={control}
      render={({ field, fieldState: { error } }) => (
        <FormControl
          {...formControl}
          size="lg"
          className="w-full mb-3"
          isInvalid={!!error?.message}
        >
          {label && (
            <FormControlLabel className="mb-1">
              <FormControlLabelText>{label}</FormControlLabelText>
            </FormControlLabel>
          )}

          <Input>
            <InputField
              {...inputProps}
              value={field.value}
              onChangeText={field.onChange}
              type={isPasswordVisible ? "text" : "password"}
            />

            <InputSlot className="pr-3" onPress={handleState}>
              <InputIcon
                // @ts-ignore
                as={isPasswordVisible ? EyeIcon : EyeOffIcon}
                className="text-gray-500"
              />
            </InputSlot>
          </Input>

          {helperText && (
            <FormControlHelper>
              <FormControlHelperText>{helperText}</FormControlHelperText>
            </FormControlHelper>
          )}

          <FormControlError>
            <FormControlErrorIcon size="sm" as={AlertCircleIcon} />
            <FormControlErrorText size="sm">
              {error?.message}
            </FormControlErrorText>
          </FormControlError>
        </FormControl>
      )}
    />
  );
}
