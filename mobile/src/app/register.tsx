import { Link, router } from "expo-router";
import { Container } from "../components/container";
import { Text, View } from "react-native";
import { useAuthContext } from "../hooks/use-auth-context";
import { useState } from "react";
import { useForm } from "react-hook-form";
import { yupResolver } from "@hookform/resolvers/yup";
import { YupRegisterSchema } from "../schemas/auth/register";
import FormProvider from "../components/hook-form/form-provider";
import { RHFTextField } from "../components/hook-form/rhf-text-field";
import RHFPasswordField from "../components/hook-form/rhf-password-field";
import { Button } from "../components/button";
import { HStack } from "@/components/ui/hstack";

type FormDataProps = {
  name: string;
  email: string;
  password: string;
  confirmPassword: string;
};

export default function Page() {
  const { register, isLoadingAccount } = useAuthContext();

  const [errorText, setErrorText] = useState<string>("");
  const [role, setRole] = useState<"manager" | "client">("client");

  const methods = useForm<FormDataProps>({
    resolver: yupResolver(YupRegisterSchema),
  });

  const { handleSubmit } = methods;

  const onSubmit = handleSubmit(async (props) => {
    try {
      await register({ ...props, role });

      router.replace("/sign-in");
    } catch (error: any) {
      const title =
        "Não foi possível acessar conta.\nTente novamente mais tarde";

      setErrorText(error?.response?.data?.message ?? title);
    }
  });

  return (
    <Container hasHeader>
      <View className="items-center">
        <Text className="text-4xl font-bold color-primary-main">
          Good to go
        </Text>
        <Text className="text-2xl">Bem vindo 😄</Text>
      </View>

      <View className="mt-6">
        {errorText && (
          <View className="flex-row bg-error-50 p-3 mb-5 rounded-md">
            <Text className="font-bold text-error-600">{errorText}</Text>
          </View>
        )}

        <HStack className="justify-center items-center mb-5" space="xl">
          <Button
            className="flex-1"
            text="Cliente"
            variant={role === "client" ? "solid" : "link"}
            onPress={() => setRole("client")}
          />

          <Button
            className="flex-1"
            text="Lojista"
            variant={role === "manager" ? "solid" : "link"}
            onPress={() => setRole("manager")}
          />
        </HStack>

        <FormProvider methods={methods}>
          <View>
            <RHFTextField
              name="name"
              label="Nome"
              inputProps={{
                placeholder: "Digite seu nome",
              }}
            />

            <RHFTextField
              name="email"
              label="E-mail"
              inputProps={{
                placeholder: "Digite seu e-mail",
              }}
            />

            <RHFPasswordField
              name="password"
              label="Senha"
              inputProps={{
                placeholder: "Digite sua senha",
              }}
            />

            <RHFPasswordField
              name="confirmPassword"
              label="Confirme sua senha"
              inputProps={{
                placeholder: "Digite sua senha novamente",
              }}
            />
          </View>

          <Button
            text="Cadastrar"
            className="mt-6"
            isLoading={isLoadingAccount}
            onPress={onSubmit}
          />
        </FormProvider>
      </View>

      <View className="flex-row gap-1 justify-center mt-16">
        <Text className="text-md text-center">Já tem uma conta?</Text>
        <Link
          href="/sign-in"
          className="text-md text-center font-bold color-primary-main"
        >
          Entrar
        </Link>
      </View>
    </Container>
  );
}
