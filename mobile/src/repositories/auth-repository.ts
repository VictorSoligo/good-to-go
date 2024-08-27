import {
  ChangePasswordType,
  LoginType,
  ResetPassworType,
  ReturnLoginType,
} from "../types/auth";
import { axiosInstance } from "../utils/axios";

const authRepository = {
  myAccount: async () => {
    const { data } = await axiosInstance.get("/auth/my-account");

    return data as ReturnLoginType;
  },
  login: async (login: LoginType) => {
    const { data } = await axiosInstance.post("/auth/login", login);

    return data as ReturnLoginType;
  },
  logout: async (refreshToken: string) => {
    await axiosInstance.post("/auth/logout", { refreshToken });
  },
  changePassword: async (changePassword: ChangePasswordType) => {
    await axiosInstance.post("/auth/change-password", changePassword);
  },
  forgotPassword: async (email: string) => {
    await axiosInstance.post("/auth/forgot-password", { email });
  },
  resetPassword: async (resetPasswordBody: ResetPassworType) => {
    await axiosInstance.post("/auth/reset-password", resetPasswordBody);
  },
};

export default authRepository;
