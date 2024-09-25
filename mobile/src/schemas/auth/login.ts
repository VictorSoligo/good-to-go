import * as Yup from "yup";

import { YupEmailRequired, YupPassword } from "../utils";

export const YupLoginSchema = Yup.object({
  email: YupEmailRequired,
  password: YupPassword,
});
