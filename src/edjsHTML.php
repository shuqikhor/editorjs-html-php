<?php

require_once(__DIR__ . "/edjsTransforms.php");

class edjsHTML {
	use edjsTransforms;

	public function __construct() {}
	
	static public function parse ($data) {
		if (!is_array($data)) $data = self::normalise_json($data);
		return array_map([static::class, 'parse_block'], $data['blocks']);
	}

	static public function parse_block ($block) {
		if (!is_array($block)) $block = self::normalise_json($block);
		return method_exists(static::class, $block['type']) ? static::{$block['type']}($block) : self::parse_function_error($block['type']);
	}
	
	static public function parse_strict ($data) {
		if (!is_array($data)) $data = self::normalise_json($data);
		$parser_free_blocks = static::validate($data);

		if (count($parser_free_blocks)) {
			throw new \Exception("Parser functions missing for blocks: " . implode(", ", $parser_free_blocks));
		}

		$parsed = [];

		foreach ($data['blocks'] as $block) {
			if (!method_exists(static::class, $block['type'])) {
				throw self::parse_function_error($block['type']);
			}

			$parsed[] = static::{$block['type']}($block);
		}

		return $parsed;
	}

	static public function validate ($data) {
		if (!is_array($data)) $data = self::normalise_json($data);
		$blocks = $data['blocks'];

		$types = array_map(function ($block) { return $block['type']; }, $blocks);
		$types = array_unique($types);

		$parser_keys = get_class_methods(static::class);

		return array_filter($types, function ($type) use ($parser_keys) {
			return !in_array($type, $parser_keys);
		});
	}

	static protected function normalise_json ($data) {
		if (is_string($data)) return json_decode($data, true);
		if (is_array($data)) return $data;
		if (is_object($data)) return self::object_to_array($data);
	}

	static protected function object_to_array ($data) {
		$result = [];
		foreach ($data as $key => $value) {
			$result[$key] = (is_object($value) || is_array($value)) ? self::object_to_array($value) : $value;
		}
		return $result;
	}

	static protected function parse_function_error (string $type) {
		return new \Exception("The parser function of type \"{$type}\" is not defined.\nDefine your custom parser functions as: https://github.com/shuqikhor/editorjs-html-php#extend-for-custom-blocks");
	}
}
