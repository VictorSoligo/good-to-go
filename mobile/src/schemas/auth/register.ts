import * as Yup from "yup";

import {
  YupConfirmPasswordRequired,
  YupEmailRequired,
  YupPassword,
} from "../utils";

export const YupRegisterSchema = Yup.object({
  name: Yup.string().required("Nome é obrigatório"),
  email: YupEmailRequired,
  password: YupPassword,
  confirmPassword: YupConfirmPasswordRequired("password"),
});
