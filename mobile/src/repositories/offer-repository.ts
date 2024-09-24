import { IOffer } from "../types/offer";
import { axiosInstance } from "../utils/axios";

export class OfferRepository {
  static async getOffers() {
    const { data } = await axiosInstance.get("/offers/active");

    return data.offers as IOffer[];
  }

  static async getOfferById(offerId: string) {
    const { data } = await axiosInstance.get(`/offers/${offerId}/details`);

    return data.offer as IOffer;
  }

  static async cancelOffer(offerId: string) {
    await axiosInstance.patch(`/offers/${offerId}/cancel`);
  }
}
