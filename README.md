# editorjs-html (PHP Port)
This is a PHP port of [editorjs-html](https://github.com/pavittarx/editorjs-html) by [@pavittarx](https://github.com/pavittarx).

`editorjs-html` is a utility to parse [Editor.js](https://editorjs.io/) clean data (JSON) to HTML.
  - Use it with any PHP framework of your choice.
  - Fast, Efficient and Lightweight. 
  - Fully customizable to the core. 
  - Supports basic Editor.js blocks which are customizable as well.
  - Extendable for any new or custom Editor.js blocks.

**[Note]** *You don't actually need to convert Editor.js data to HTML for display. For that purpose, simply embed Editor.js in read-only mode.*

**[Note]** *This library is mainly for those who needs to convert Editor.js clean data to HTML for other uses, such as for API calls to other systems, or for migration to another editor.*

# Installation

### Composer

```shell
composer require sqkhor/editorjs-html
```

## Usage

```php
// Get an array of HTML based on original blocks
$result = edjsHTML::parse($editorjs_clean_data);

// Enclose in <section> for display
$sections = array_map(function ($section) {
  return "<section>$section</section>";
}, $result);

// Join for output
$html = implode("", $sections);
echo $html;
```

## Updates 

See [Releases](https://github.com/shuqikhor/editorjs-html-php/releases)

## Docs

### Supported Block Types 

* Header (H1-H6)
* Lists (Ordered & Unordered)
* Nested Lists
* Image
* Delimiter 
* Paragraph
* Quote
* Code
* Embed

### Accepted Data Format
The data passed to `parse()` or `parse_strict()` could be either an undecoded JSON string, or any JSON-decoded format (supports both stdClass and associative array)

### Parse Entire Editor.js Data

```php
  $HTML = edjsHTML::parse($editorjs_data);
  // returns an array of html strings per block
  var_export($HTML);
```

### Parse Entire Editor.js Data (Strict Mode)

```php
try {
  $HTML = edjsHTML::parse_strict($editorjs_data);

  // in case of success, returns an array of strings
  var_export($HTML)
} catch (\Exception $e) {
  // returns an error when data is invalid
}
```

### Parse Single Clean Data Block

```php
  $block_HTML = edjsHTML::parse_block($editorjs_data_block);
  // returns a string of html for this block
  var_export(block_HTML);
```
### Get a list of missing parser functions 

```php
  // returns a list of missing parser functions
  $block_HTML = edjsHTML::validate($editorjs_data);
  var_export(block_HTML);
```

### Extend For Custom Blocks 
To add your own parser functions for unsupported block types, simply extend the `edjsHTML` class with the block parsers as static methods.

You can even override existing block parsers.

**[Note]** *The name of the methods must match with Editor.js custom block type.*

#### Example:

```js
// Your custom editorjs generated block
{
  type: "custom",
  data: {
    text: "Hello World"
  }
}
```

```php
// Parse this block in editorjs-html
class CustomParser extends edjsHTML {
  static public function custom ($block) {
    return "<div class=\"custom-block\">{$block['data']['text']}</div>";
  }
}

const HTML = CustomParser::parse($editorjs_data);
```

## Design Notes
**[Note]** *This section is not important.*

Unlike Javascript/Typescript, which the original library is built on, you can't pass a function as a variable in PHP. This limits the ways we could pass a parser function to the main class.

Therefore I was left with 2 options:
1. Have separate classes for the main operation and the block parsers. To add your own block parser, extend the parser class and pass it to the main class.
2. Have a single class for everything. To add your own block, simply extend the one-and-only class.

#1 is the proper way.  
#2 is easier to use.

I opted for #2.

## License 
MIT Public License

## Author 
[@shuqikhor](https://sqkhor.com)
based on works by [@pavittarx](https://github.com/pavittarx)

