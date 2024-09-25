import AsyncStorage from "@react-native-async-storage/async-storage";
import { AUTH_STORAGE } from ".";
import { axiosInstance } from "../utils/axios";

function setAxiosSession(token: string) {
  axiosInstance.defaults.headers.common.Authorization = `Bearer ${token}`;
}

async function removeSession() {
  await AsyncStorage.removeItem(AUTH_STORAGE);
  delete axiosInstance.defaults.headers.common.Authorization;
}

export { removeSession, setAxiosSession };
