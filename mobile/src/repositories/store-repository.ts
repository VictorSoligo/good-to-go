import { IStore } from "../types/store";
import { axiosInstance } from "../utils/axios";

export class StoreRepository {
  static async getStores() {
    const { data } = await axiosInstance.get("/stores");

    return data.stores as IStore[];
  }
}
