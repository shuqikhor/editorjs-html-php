<?php

require_once("../src/edjsHTML.php");
header("Content-type: text/plain");




$data = file_get_contents("./data.json");

comment('edjsHTML::parse_strict($data)');
var_export(edjsHTML::parse_strict($data));
divider();

comment('edjsHTML::validate($data)');
var_export(edjsHTML::validate($data));
divider();




// test for custom parser
class CustomParser extends edjsHTML {
	static public function custom ($block) {
		return "<custom>success</custom>";
	}
	static public function paragraph ($block) {
		return "<p>override test</p>";
	}
}

comment('CustomParser::parse_strict($custom_blocks)');
var_export(CustomParser::parse_strict([
	"blocks" => [
		[
			"type" => "custom",
			"data" => []
		]
	]
]));
divider();

comment('CustomParser::parse_strict($custom_blocks)');
var_export(CustomParser::parse_strict($data));
divider();

comment('CustomParser::validate($data)');
var_export(CustomParser::validate($data));
divider();




// test for issue #21
comment('<p>foo bar</p>');
var_export(edjsHTML::parse_block(["type" => "paragraph", "data" => ["text" => "foo bar"]]) === '<p>foo bar</p>');
divider();




// test for issue #39
comment('<p style="text-align:right;">foo bar</p>');
var_export(edjsHTML::parse_block(["type" => "paragraph", "data" => ["text" => "foo bar", "alignment" => "right"]]) === '<p style="text-align:right;">foo bar</p>');
divider();

comment('<p style="text-align:justify;">foo bar</p>');
var_export(edjsHTML::parse_block(["type" => "paragraph", "data" => ["text" => "foo bar", "alignment" => "justify"]]) === '<p style="text-align:justify;">foo bar</p>');
divider();

comment('<p style="text-align:center;">foo bar</p>');
var_export(edjsHTML::parse_block(["type" => "paragraph", "data" => ["text" => "foo bar", "alignment" => "center"]]) === '<p style="text-align:center;">foo bar</p>');
divider();

comment('<p style="text-align:left;">foo bar</p>');
var_export(edjsHTML::parse_block(["type" => "paragraph", "data" => ["text" => "foo bar", "alignment" => "left"]]) === '<p style="text-align:left;">foo bar</p>');
divider();

comment('<p>foo bar</p>');
var_export(edjsHTML::parse_block(["type" => "paragraph", "data" => ["text" => "foo bar", "alignment" => "wrong type"]]) === '<p>foo bar</p>');
divider();

comment('<p style="text-align:right;">foo bar</p>');
var_export(edjsHTML::parse_block(["type" => "paragraph", "data" => ["text" => "foo bar", "align" => "right"]]) === '<p style="text-align:right;">foo bar</p>');
divider();

comment('<p style="text-align:justify;">foo bar</p>');
var_export(edjsHTML::parse_block(["type" => "paragraph", "data" => ["text" => "foo bar", "align" => "justify"]]) === '<p style="text-align:justify;">foo bar</p>');
divider();

comment('<p style="text-align:center;">foo bar</p>');
var_export(edjsHTML::parse_block(["type" => "paragraph", "data" => ["text" => "foo bar", "align" => "center"]]) === '<p style="text-align:center;">foo bar</p>');
divider();

comment('<p style="text-align:left;">foo bar</p>');
var_export(edjsHTML::parse_block(["type" => "paragraph", "data" => ["text" => "foo bar", "align" => "left"]]) === '<p style="text-align:left;">foo bar</p>');
divider();

comment('<p>foo bar</p>');
var_export(edjsHTML::parse_block(["type" => "paragraph", "data" => ["text" => "foo bar", "align" => "wrong type"]]) === '<p>foo bar</p>');
divider();




function comment ($comment) {
	echo "// $comment\n\n";
}

function divider () {
	echo str_repeat("\n", 5);
	echo "/* " . str_repeat("-", 50) . " */";
	echo str_repeat("\n", 5);
}
