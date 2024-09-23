export type IStore = {
  id: string;
  name: string;
  adress: string;
  ownerId: string;
  attachment: {
    id: string;
    url: string;
  };
  createdAt: Date;
};
