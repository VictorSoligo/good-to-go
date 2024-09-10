import { ref, string } from "yup";

// password validation
export const YupPassword = string()
  .required("Senha é obrigatória")
  .min(6, "Senha deve ter ao menos 6 caracteres");

export function YupConfirmPasswordRequired(password_field: string) {
  return string()
    .required("Confirmação de senha é obrigatória")
    .oneOf([ref(password_field), ""], "Senhas não batem");
}

// name validation
export const YupNameRequired = string()
  .required("Nome é obrigatório")
  .min(3, "Nome deve ter ao menos 3 caracteres");

export const YupNameOptional = string().optional();

// email validation
export const YupEmailRequired = string()
  .required("Email é obrigatório")
  .email("Email deve ser um endereço de email válido");

export const YupEmailOptional = string().optional().nullable();

// cnpj validation
export const YupCNPJRequired = string()
  .required("CNPJ é obrigatório")
  .matches(/^\d{2}\.\d{3}\.\d{3}\/\d{4}\.\d{2}$/, "CNPJ inválido");

export const YupCNPJOptional = string().optional().nullable();

// cpf validation
export const YupCPFRequired = string()
  .required("CPF é obrigatório")
  .matches(/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/, "CPF inválido");

export const YupCPFOptional = string().optional();

// rg validation
export const YupRgRequired = string()
  .required("RG é obrigatório")
  .matches(/^\d{1}\.\d{3}\.\d{3}$/, "RG inválido");

export const YupRgOptional = string().optional().nullable();

// cep
export const YupCEPRequired = string()
  .required("CEP é obrigatório")
  .matches(/^\d{2}\.\d{3}\-\d{3}$/, "CEP inválido");

export const YupCEPOptional = string().optional().nullable();

//phone validation
export const YupPhoneRequired = string()
  .required("Telefone é obrigatório")
  .matches(/^\(?\d{2}\)?[\s-]?[\s9]?\d{4}-?\d{4}$/, "Telefone inválido");

export const YupPhoneOptional = string()
  .optional()
  .matches(/^\([1-9]{2}\)\s*[0-9]{5}-[0-9]{4}$/, "Telefone celular inválido");

export const YupVerifyCode = string().matches(/^\d{6}$/, "Código inválido");
