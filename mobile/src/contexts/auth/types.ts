import { RegisterType } from "@/src/types/auth";
import { IAccount } from "../../types/account";

// ----------------------------------------------------------------------

export type ActionMapType<M extends { [index: string]: any }> = {
  [Key in keyof M]: M[Key] extends undefined
    ? {
        type: Key;
      }
    : {
        type: Key;
        payload: M[Key];
      };
};

export type AuthUserType = null | IAccount;

export type AuthStateType = {
  status?: string;
  loading: boolean;
  account: AuthUserType;
};

export type AuthContextType = {
  account: AuthUserType;
  loading: boolean;
  isLoadingAccount: boolean;
  authenticated: boolean;
  unauthenticated: boolean;
  register: (register: RegisterType) => Promise<void>;
  login: (email: string, password: string) => Promise<void>;
  logout: () => Promise<void>;
};
