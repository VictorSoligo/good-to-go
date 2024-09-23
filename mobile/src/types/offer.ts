export type IOffer = {
  id: string;
  description: string;
  price: number;
  store: {
    id: string;
    name: string;
  };
  attachments: {
    id: string;
    url: string;
  }[];
  availableUntil: Date;
  canceledAt: Date | null;
  createdAt: Date;
};
