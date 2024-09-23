import { IOffer } from "../types/offer";
import { axiosInstance } from "../utils/axios";

export class OfferRepository {
  static async getOffers() {
    const { data } = await axiosInstance.get("/offers/active");

    return data.offers as IOffer[];
  }
}
