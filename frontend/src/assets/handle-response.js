import gz from "./gz";

export default (response) => {
  if (response.status != "ok") {
    throw `Error: ${response.message}`;
  }

  if (response.compressed) response.data = gz.decompress(response.data);

  return response.data;
};
