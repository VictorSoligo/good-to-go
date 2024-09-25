import { mixed, object, string } from "yup";

export const YupCreateStoreSchema = object({
  name: string().required("Informe o nome da loja"),
  adress: string().required("Informe o endereço da loja"),
  image: mixed().required("Selecione uma imagem para a loja"),
});
