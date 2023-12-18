<?php

trait edjsTransforms {
	static public function test () {
		return "test\n";
	}

	static public function delimiter () {
		return "<br/>";
	}
	
	static public function header ($block) {
		$data = $block['data'];
		return "<h{$data['level']}>{$data['text']}</h{$data['level']}>";
	}
	
	static public function paragraph ($block) {
		$data = $block['data'];
		$align_type = ['left', 'right', 'center', 'justify'];
		$paragraph_align = $data['alignment'] ?? $data['align'] ?? null;

		if (!empty($paragraph_align) && in_array($paragraph_align, $align_type)) {
			return "<p style=\"text-align:$paragraph_align;\">{$data['text']}</p>";
		} else {
			return "<p>{$data['text']}</p>";
		}
	}

	static protected function list_recursor ($items, $list_style) {
		$list = array_map(function ($item) use ($list_style) {
			if (empty($item['content']) && empty($item['items'])) {
				return "<li>{$item}</li>";
			}

			$list = "";
			if ($item['items']) $list = self::list_recursor($item['items'], $list_style);
			if (!empty($item['content'])) return "<li>{$item['content']}</li>" . $list;;
		}, $items);

		return "<{$list_style}>" . implode("", $list) . "</{$list_style}>";
	}
	
	static public function list ($block) {
		$data = $block['data'];
		$list_style = $data['style'] === "unordered" ? "ul" : "ol";
		
		return self::list_recursor($data['items'], $list_style);
	}
	
	static public function image ($block) {
		$data = $block['data'];
		$caption = $data['caption'] ?? "Image";

		$path = !empty($data['file']) && !empty($data['file']['url']) ? $data['file']['url'] : $data['url'];

		return "<img src=\"$path\" alt=\"$caption\" />";
	}
	
	static public function quote ($block) {
		$data = $block['data'];
		return "<blockquote>{$data['text']}</blockquote> - {$data['caption']}";
	}
	
	static public function code ($block) {
		$data = $block['data'];
	  return "<pre><code>{$data['code']}</code></pre>";
	}
	
	static public function embed ($block) {
		$data = $block['data'];
		switch ($data['service']) {
			case "vimeo":
				return "<iframe src=\"{$data['embed']}\" height=\"{$data['height']}\" frameborder=\"0\" allow=\"autoplay; fullscreen; picture-in-picture\" allowfullscreen></iframe>";
			case "youtube":
				return "<iframe width=\"{$data['width']}\" height=\"{$data['height']}\" src=\"{$data['embed']}\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>";
			default:
				throw new \Exception("Only Youtube and Vime Embeds are supported right now.");
		}
	}
}
