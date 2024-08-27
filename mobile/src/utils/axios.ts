import axios from "axios";
// config
import { HOST_API } from "../config-global";

const axiosInstance = axios.create({
  baseURL: HOST_API,
});

// ----------------------------------------------------------------------

export { axiosInstance };
