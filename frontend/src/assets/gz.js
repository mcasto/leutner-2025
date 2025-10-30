import pako from "pako";
import { Buffer } from "buffer";

export default {
  compress: (str) => Buffer.from(pako.deflateRaw(str)).toString("base64"),
  decompress: (str) =>
    pako.inflateRaw(Buffer.from(str, "base64"), { to: "string" }),
};
