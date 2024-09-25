import { mixed, number, object, string } from "yup";

export const YupCreateOfferSchema = object({
  description: string().required("Informe a descrição da oferta"),
  price: number().required("Informe o preço da oferta"),
  image: mixed().required("Informe a imagem da oferta"),
  productName: string().required("Informe o nome do produto"),
  availableUntil: string()
    .matches(
      /\d{2}\/\d{2}\/\d{4}/,
      "Informe a data de validade no formato DD-MM-YYYY"
    )
    .required("Informe a data de validade da oferta"),
});
