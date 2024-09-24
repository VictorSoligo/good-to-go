import { Controller, useFormContext } from "react-hook-form";
import * as ImagePicker from "expo-image-picker";
import { router } from "expo-router";
import {
  FormControl,
  FormControlError,
  FormControlErrorIcon,
  FormControlErrorText,
  FormControlLabel,
  FormControlLabelText,
} from "@/components/ui/form-control";
import { Center } from "@/components/ui/center";
import { ActivityIndicator, Image, TouchableOpacity } from "react-native";
import { VStack } from "@/components/ui/vstack";
import { AlertCircleIcon } from "lucide-react-native";
import { useState } from "react";

type RHFCameraProps = {
  name: string;
  label?: string;
};

export default function RHFLibrary({ name, label }: RHFCameraProps) {
  const [photoIsLoading, setPhotoIsLoading] = useState(false);

  const { control } = useFormContext();

  return (
    <Controller
      name={name}
      control={control}
      render={({ field, fieldState: { error } }) => {
        const isInvalid = !!error;

        async function handleLibraryPhoto() {
          setPhotoIsLoading(true);

          try {
            const { granted } =
              await ImagePicker.requestCameraPermissionsAsync();

            if (!granted) {
              return;
            }

            const photoSelected = await ImagePicker.launchImageLibraryAsync({
              mediaTypes: ImagePicker.MediaTypeOptions.Images,
              quality: 0.5,
            });

            if (photoSelected.canceled) {
              return;
            }

            const asset = photoSelected.assets[0];

            if (asset) {
              field.onChange(asset);
            }
          } catch (error) {
            router.back();
          } finally {
            setPhotoIsLoading(false);
          }
        }

        return (
          <FormControl className="flex-1" isInvalid={isInvalid}>
            {label && (
              <FormControlLabel className="mb-1">
                <FormControlLabelText>{label}</FormControlLabelText>
              </FormControlLabel>
            )}

            {photoIsLoading ? (
              <Center className="flex-1 bg-gray-100 rounded-lg">
                <ActivityIndicator
                  size="large"
                  className="color-primary-main"
                />
              </Center>
            ) : (
              <TouchableOpacity
                onPress={handleLibraryPhoto}
                activeOpacity={0.7}
                style={{ flex: 1 }}
              >
                {field?.value?.uri ? (
                  <Image
                    source={{ uri: field?.value?.uri }}
                    className="rounded-lg"
                    style={{ width: "100%", height: "100%" }}
                    alt={name}
                  />
                ) : (
                  <VStack className="flex-1 bg-gray-100 rounded-lg"></VStack>
                )}
              </TouchableOpacity>
            )}

            <FormControlError>
              <FormControlErrorIcon as={AlertCircleIcon} />
              <FormControlErrorText>
                {/* @ts-ignore */}
                {error?.message || error?.base64?.message}
              </FormControlErrorText>
            </FormControlError>
          </FormControl>
        );
      }}
    />
  );
}
