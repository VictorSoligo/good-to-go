export type LoginType = {
  email: string;
  password: string;
};

export type ReturnLoginType = {
  accessToken: string;
};

export type RegisterType = {
  email: string;
  password: string;
  name: string;
  role: "manager" | "client";
};
