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
import { AlertCircleIcon } from "@/components/ui/icon";
import { Input, InputField } from "@/components/ui/input";
import { ComponentProps } from "react";
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
  sexo?: ComponentProps<typeof Input>;
};

export function RHFTextField({
  name,
  label,
  helperText,
  inputProps,
  formControl,
  sexo,
}: Props) {
  const { control } = useFormContext();

  return (
    <Controller
      name={name}
      control={control}
      render={({ field, fieldState: { error } }) => (
        <FormControl
          {...formControl}
          className="w-full mb-3"
          isInvalid={!!error?.message}
        >
          {label && (
            <FormControlLabel className="mb-1">
              <FormControlLabelText>{label}</FormControlLabelText>
            </FormControlLabel>
          )}

          <Input {...sexo}>
            <InputField
              autoCapitalize="none"
              {...inputProps}
              value={field.value}
              onChangeText={field.onChange}
              type="text"
            />
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
