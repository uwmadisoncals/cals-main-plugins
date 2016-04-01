<?php
/*
Plugin Name: Search Suggest
Plugin URI: http://yoast.com/wordpress/search-suggest/
Description: Offers a spelling suggestion for a certain word, useful on search pages when no result was found. Include <code>spell_suggest();</code>, or <code>related_searches();</code> for related queries.
Author: Joost de Valk
Version: 1.1
Author URI: http://yoast.com/
*/

$yahooappid = '3uiRXEzV34EzyTK7mz8RgdQABoMFswanQj_7q15.wFx_N4fv8_RPdxkD5cn89qc-';

require_once(ABSPATH . 'wp-includes/class-snoopy.php');

function spell_suggest($full = true) {
	global $yahooappid, $s;
	
	$query 	= "http://search.yahooapis.com/WebSearchService/V1/spellingSuggestion?appid=$yahooappid&query=".$s."&output=php";
	$wpurl 	= get_bloginfo('wpurl'); 
	$snoopy = new Snoopy;
	
	$snoopy->fetch($query);
	$resultset = unserialize($snoopy->results);
	if (isset($resultset['ResultSet']['Result'])) {
		if (is_string($resultset['ResultSet']['Result'])) {
			$output = '<a href="'.$wpurl.'?s='.urlencode($resultset['ResultSet']['Result']).'" rel="nofollow">'.$resultset['ResultSet']['Result'].'</a>';
		} else {
			foreach ($resultset['ResultSet']['Result'] as $result) {
				$output .= '<a href="'.$wpurl.'?s='.urlencode($result).'" rel="nofollow">'.$result.'</a>, ';
			}
		}
		if ($full) {
			echo "<p>Did you mean <strong>".$output."</strong>?</p>";
		} else {
			return "<p>Did you mean <strong>".$output."</strong>?</p>";
		}
	} else {
		return false;
	}
}

function related_searches($full = true) {
	global $yahooappid, $s;
	
	$query 	= "http://search.yahooapis.com/WebSearchService/V1/relatedSuggestion?appid=$yahooappid&query=$s&results=5&output=php";
	$wpurl 	= get_bloginfo('wpurl'); 
	$snoopy = new Snoopy;
	
	$snoopy->fetch($query);
	$resultset = unserialize($snoopy->results);
	if (isset($resultset['ResultSet']) && $resultset['ResultSet'] != "\n") {
		if (is_string($resultset['ResultSet']['Result'])) {
			$result = $resultset['ResultSet']['Result'];
			$output = '<a href="'.$wpurl.'?s='.urlencode($result).'" rel="nofollow">'.$result.'</a>';
		} else {	
			foreach ($resultset['ResultSet']['Result'] as $result) {
				if ($output != "") {
					$output .= ", ";
				}
				$output .= '<a href="'.$wpurl.'?s='.urlencode($result).'" rel="nofollow">'.$result.'</a>';
			}
		}
		if ($full) {
			echo "<p>You might try these searches as well: ".$output."</p>";
		} else {
			return "<p>You might try these searches as well: ".$output."</p>";
		}
	} else {
		return false;
	}
}

?>