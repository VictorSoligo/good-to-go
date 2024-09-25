import { axiosInstance } from "../utils/axios";

export class StorageRepository {
  static async uploadImage(formData: any) {
    const { data } = await axiosInstance.post<{ id: string }>(
      "/attachments",
      formData,
      {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      }
    );

    return data;
  }
}
