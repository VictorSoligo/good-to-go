import AsyncStorage from "@react-native-async-storage/async-storage";
import { AUTH_STORAGE } from ".";

type StorageAuthTokenProps = {
  token: string;
  refreshToken: string;
};

async function storageSession({ token, refreshToken }: StorageAuthTokenProps) {
  await AsyncStorage.setItem(
    AUTH_STORAGE,
    JSON.stringify({ token, refreshToken })
  );
}

async function getSession() {
  const response = await AsyncStorage.getItem(AUTH_STORAGE);

  const { token, refreshToken }: StorageAuthTokenProps = response
    ? JSON.parse(response)
    : {};

  return { token, refreshToken };
}

export { getSession, storageSession };
