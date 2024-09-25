import React, { useState } from "react";
import { Text, View } from "react-native";
import { Container } from "../components/container";
import { useForm } from "react-hook-form";
import { yupResolver } from "@hookform/resolvers/yup";
import { YupLoginSchema } from "../schemas/auth/login";
import { useAuthContext } from "../hooks/use-auth-context";
import { useToast } from "@/components/ui/toast";
import FormProvider from "../components/hook-form/form-provider";
import { RHFTextField } from "../components/hook-form/rhf-text-field";
import RHFPasswordField from "../components/hook-form/rhf-password-field";
import { Button } from "../components/button";
import { VStack } from "@/components/ui/vstack";
import { AlertCircleIcon } from "@/components/ui/icon";
import { Link, router, Stack } from "expo-router";

type FormDataProps = {
  email: string;
  password: string;
};

export default function Page() {
  const { login, isLoadingAccount } = useAuthContext();

  const [errorText, setErrorText] = useState<string>("");

  const methods = useForm<FormDataProps>({
    resolver: yupResolver(YupLoginSchema),
  });

  const { handleSubmit } = methods;

  const onSubmit = handleSubmit(async ({ email, password }) => {
    try {
      await login(email, password);

      router.replace("/");
    } catch (error: any) {
      const title =
        "Não foi possível acessar conta.\nTente novamente mais tarde";

      setErrorText(error?.response?.data?.message ?? title);
    }
  });

  return (
    <Container>
      <Stack.Screen
        options={{
          headerShown: false,
        }}
      />

      <View className="items-center p-6 ">
        <Text className="text-6xl font-bold color-primary-main">
          Good to go
        </Text>
        <Text className="text-2xl">Bem vindo de volta! 😄</Text>
      </View>

      <View>
        <Text className="my-8 text-xl font-bold text-center">
          Acessar Conta
        </Text>

        <FormProvider methods={methods}>
          <VStack className="mb-6">
            {errorText && (
              <View className="flex-row bg-error-50 p-3 mb-5 rounded-md">
                <Text className="font-bold text-error-600">{errorText}</Text>
              </View>
            )}

            <RHFTextField
              name="email"
              label="E-mail"
              inputProps={{
                placeholder: "ex: joãodasilva@email.com",
              }}
            />

            <RHFPasswordField
              name="password"
              label="Senha"
              inputProps={{
                placeholder: "ex: insira sua senha",
              }}
            />
          </VStack>
        </FormProvider>

        <Button text="Entrar" isLoading={isLoadingAccount} onPress={onSubmit} />
      </View>

      <View className="flex-row gap-1 justify-center mt-16">
        <Text className="text-md text-center">Não tem uma conta?</Text>
        <Link
          href="/register"
          className="text-md text-center font-bold color-primary-main"
          onPress={() => {}}
        >
          Cadastrar-se
        </Link>
      </View>
    </Container>
  );
}
