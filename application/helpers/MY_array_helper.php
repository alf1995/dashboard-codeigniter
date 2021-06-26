<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


if ( ! function_exists('array_recursive_search'))
{
	function array_recursive_search($haystack, $needle, $index = null)
	{
	    $aIt     = new RecursiveArrayIterator($haystack);
	    $it    = new RecursiveIteratorIterator($aIt);
	    while($it->valid())
	    {       
	        if (((isset($index) AND ($it->key() == $index)) OR (!isset($index))) AND ($it->current() == $needle)) {
	            return $aIt->key();
	        }
	        $it->next();
	    }
	    return FALSE;
	}
}

if ( ! function_exists('array_get'))
{
	function array_get($array, $searched, $index)
	{
		$aIt = new RecursiveArrayIterator($array);
		$it = new RecursiveIteratorIterator($aIt);
		
		while($it->valid())
		{
			if (((isset($index) AND ($it->key() == $index)) OR (!isset($index))) AND ($it->current() == $searched))
			{
				$c[] = $aIt->current();
			}
			$it->next();
		}
		return $c;
	}
}


