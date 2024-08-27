export type IAccount = {
  id: string;
  name: string;
  email: string;
  phone?: string;
  createdAt: Date;
  updatedAt: Date;
};

export type UpdateAccountBody = {
  name: string;
  email: string;
  phone?: string;
};
