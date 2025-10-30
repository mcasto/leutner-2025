const { readFileSync, readdirSync, writeFileSync } = require("fs");
const { join } = require("path");
const cheerio = require("cheerio");

const articleFile = readdirSync(join(__dirname, "article"))
  .filter((filename) => {
    return filename.substring(filename.length - 4) == "html";
  })
  .map((filename) => join(__dirname, "article", filename))
  .shift();

const html = readFileSync(articleFile).toString();

const $ = cheerio.load(html);

const paras = [];

$("p").each((i, el) => {
  paras.push(`<p>${$(el).html()}</p>`);
});

writeFileSync(
  join(__dirname, "output.html"),
  `<h1>${$("title").text()}</h1> \n\n ${paras.join("\n")}`
);
