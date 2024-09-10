import { object } from "yup";
import { YupEmailRequired } from "../utils";

export const ForgotPasswordSchema = object({
  email: YupEmailRequired,
});
