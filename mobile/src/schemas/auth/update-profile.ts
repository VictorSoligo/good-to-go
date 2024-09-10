import * as Yup from "yup";

import { YupEmailRequired, YupNameRequired, YupPhoneOptional } from "../utils";

export const YupUpdateProfileSchema = Yup.object({
  name: YupNameRequired,
  email: YupEmailRequired,
  phone: YupPhoneOptional,
});
