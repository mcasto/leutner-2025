const { readFileSync, writeFileSync, copyFileSync } = require("fs");
const cheerio = require("cheerio");
const { join, basename, relative, dirname } = require("path");
const TurndownService = require("turndown");
const { argv } = require("process");
const uniqid = require("uniqid");

const chlFile = argv[2];
const filename = argv[3];
const imagePath = argv[4];

const srcPath = `/${relative(join(__dirname, "../../.."), imagePath)}`;

const outputFile = join(dirname(__dirname), filename);

const turndownService = new TurndownService();

const html = readFileSync(chlFile).toString();

const $ = cheerio.load(html);

$("img").each((i, el) => {
  // get img src
  const src = $(el).attr("src");
  const srcFile = join(dirname(chlFile), src);
  const outputFile = join(imagePath, basename(srcFile));
  copyFileSync(srcFile, outputFile);

  $(el).remove();
});

const paras = [];
$("p").each((i, el) => {
  paras.push(`<p>${$(el).html()}</p>`);
});

const output = paras.join("");
const markdown = turndownService.turndown(output);

writeFileSync(outputFile, markdown);
