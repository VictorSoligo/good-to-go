import AsyncStorage from "@react-native-async-storage/async-storage";
import { AUTH_STORAGE } from ".";

type StorageAuthTokenProps = {
  token: string;
};

async function storageSession({ token }: StorageAuthTokenProps) {
  await AsyncStorage.setItem(AUTH_STORAGE, JSON.stringify({ token }));
}

async function getSession() {
  const response = await AsyncStorage.getItem(AUTH_STORAGE);

  const { token }: StorageAuthTokenProps = response ? JSON.parse(response) : {};

  return { token };
}

export { getSession, storageSession };
