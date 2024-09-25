import { IStore } from "../types/store";
import { axiosInstance } from "../utils/axios";

export class StoreRepository {
  static async getStores() {
    const { data } = await axiosInstance.get("/stores");

    return data.stores as IStore[];
  }

  static async getStoreById(storeId: string) {
    const { data } = await axiosInstance.get(`/stores/id/${storeId}`);

    return data.store as IStore;
  }

  static async createStore(store: {
    name: string;
    adress: string;
    attachmentId: string;
  }) {
    await axiosInstance.post<{ id: string }>("/stores", store);
  }
}
