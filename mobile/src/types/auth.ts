import { IAccount } from "./account";
import { TokensType } from "./token";

export type LoginType = {
  email: string;
  password: string;
};

export type ReturnLoginType = {
  account: IAccount;
  tokens: TokensType;
};

export type ChangePasswordType = {
  password: string;
  newPassword: string;
};

export type ResetPassworType = {
  password: string;
  code: number;
  email: string;
};
