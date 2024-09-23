import { IAccount } from "../types/account";
import { LoginType, RegisterType, ReturnLoginType } from "../types/auth";
import { axiosInstance } from "../utils/axios";

const authRepository = {
  myAccount: async () => {
    const { data } = await axiosInstance.get("/users/me");

    return data?.user as IAccount;
  },
  login: async (login: LoginType) => {
    const { data } = await axiosInstance.post("/users/sessions", login);

    return data as ReturnLoginType;
  },
  register: async (register: RegisterType) => {
    const { data } = await axiosInstance.post("/users", register);

    return data as ReturnLoginType;
  },
};

export default authRepository;
