<?php

use Cocur\Slugify\Slugify;

if (!function_exists('url_seo')) {

	function url_seo($parametros,$separator = '-'){
		$slugify = new Slugify(['separator' => $separator]);
		return $slugify->slugify($parametros);
	}
}