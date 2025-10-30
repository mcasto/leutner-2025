/**
 * 1. copy article HTML into the article/ along with its _files folder
 * 2. set the filename value below
 * 3. run script
 * 4. add to database/Leutner/articles
 *    a. _id = filename
 *    b. folder = "Medium"
 *    c. label = article title
 *    d. byline = "Carol E. Leutner" (?)
 *    e. date = current date
 *    f. url = url of article on medium
 *    g. sort_order = prev + 10
 *    h. category = appropriate category from article_categories
 * 5. upload file to Carol Leutner/api/articles
 * 6. copy db record from local to remote (as SQL, watch for ID conflict)
 */

const { readFileSync, writeFileSync, readdirSync } = require("fs");
const cheerio = require("cheerio");
const { trim } = require("lodash");
const { join, parse } = require("path");
const TurndownService = require("turndown");
const { exit } = require("process");

const mediumFile = readdirSync(join(__dirname, "article"))
  .filter((filename) => {
    return filename.substring(filename.length - 4) == "html";
  })
  .map((filename) => join(__dirname, "article", filename))
  .shift();

// set filename here
const filename = `bullying-discontent-and-moral-clarity.md`;

const outputFile = join(__dirname, filename);

const turndownService = new TurndownService();

const html = readFileSync(mediumFile).toString();

const $ = cheerio.load(html);

// get order of elements
const paragraphs = $("article:first p");
const paras = [];
let capturing = false;

paragraphs.each((i, el) => {
  if (capturing) {
    paras.push(`<p>${$(el).html()}</p>`);
  }
  if (trim($(el).text()) == "Share") {
    capturing = true;
  }
});

const output = paras.join("");
const markdown = turndownService.turndown(output);

writeFileSync(outputFile, markdown);
