import * as Yup from "yup";

import { YupConfirmPasswordRequired, YupPassword } from "../utils";

export const YupResetPasswordSchema = (maxLength: number) => {
  return Yup.object().shape({
    newPassword: YupPassword,
    confirmNewPassword: YupConfirmPasswordRequired("newPassword"),
    code: Yup.string()
      .required("Código é obrigatório")
      .min(maxLength, `Código deve conter no mínimo ${maxLength} caracteres!`)
      .max(maxLength),
  });
};

export const YupChangePasswordSchema = Yup.object().shape({
  password: YupPassword,
  newPassword: YupPassword,
  confirmNewPassword: YupConfirmPasswordRequired("newPassword"),
});
