export type IOffer = {
  id: string;
  productName: string;
  description: string;
  price: number;
  store: {
    id: string;
    name: string;
    address: string;
    ownerId: string;
  };
  attachments: {
    id: string;
    url: string;
  }[];
  availableUntil: Date;
  canceledAt: Date | null;
  createdAt: Date;
};
