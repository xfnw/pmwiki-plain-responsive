<?php if (!defined('PmWiki')) exit();
/** footnote2.php
 *	handle footnotes in a page
 *
 *	AWColley 2007-08-05 -- created footnote.php
 *	Extracted from extendmarkup.php Version 2.0.59
 *	(see http://www.pmwiki.org/wiki/Cookbook/MarkupExtensions)
 *
 *	Copyright 2007 A. W. Colley
 *	You may use this file however you see fit. I don't care.
 *
 *	Modified by Randy Brown to work with PHP 5.5 and to omit the blue line if no 
 *	footnote is on the page. This allows a group footer to include [^#^] to show 
 *	any footnotes present, without showing a blue line if there are none.
 *
 *	ExtendedMarkup.php Copyright 2004-2006 John Rankin (john.rankin@affinity.co.nz)
 *	This program is free software; you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published
 *	by the Free Software Foundation; either version 2 of the License, or
 *	(at your option) any later version.
 *
 *	THIS SOFTWARE IS PROVIDED AS IS WITHOUT ANY GUARANTEES FOR SUITABLILITY FOR
 *	ANY PURPOSE. THE COPYRIGHT HOLDERS ARE NOT RESPONSIBLE FOR ANY DAMAGES, WHETHER
 *	DIRECT, INDIRECT, OR TOTALLY UNRELATED THAT MAY ACCOMPANY, RESULT FROM, OR FOR
 *	ANY REASON OCCUR AFTER THE USE OF, PERUSAL OF, OR THINKING ABOUT THIS SOFTWARE.
 *
 *
 *	If you don't like the thin blue line, change it in the "div.footnote" style
 *	below.
 */
# Version date
$RecipeInfo['footnote2']['Version'] = '2021-06-26';

$HTMLStylesFmt['footnote2'] = "
.footnote_block_begin { 
	width: 160px; 
	border-bottom: 1px solid black;
	margin-bottom: 0.5em;
}
div.footnote {
	margin: 0 3em 0.5em 0;
	padding-left: 2em;
	font-size: 0.9em;
	position: relative;
}
div.footnote .footnote-number {
	position: absolute;
	left: 0;
	width: 1.5em;
	text-align: right;
}
div.footnote .footnote-number::after {
	content: '.';
}
";
SDV($FootnoteBacklinkCharacter, '&uArr;');

## [^footnote text^] and [^#^] to list footnotes with blue line or [^@^] to list them without
Markup("[^",'<split','/\[\^(.*?)\^\]/s',"Footnote");
Markup("checkbox", "inline", "/\[ \]/", "<input type=checkbox class=checkbox disabled>");
Markup("checkedbox", "inline", "/\[x\]/", "<input type=checkbox class=checkbox disabled checked>");

function Footnote($m) {
	static $fngroup = 1, $fncount = 0, $fntext = array();

	$fn_str = $m[1];
	if ($fn_str == "#" || $fn_str == "@") {
		if ($fncount > 0) {
			ksort($fntext);
			$out = ($fn_str == "#") ? 
					"<:block><div class='footnote_block_begin'>&nbsp;</div>\n" . implode('',$fntext) :
					implode('',$fntext);
		}
		
		$fntext = array();
		$fncount = 0;
		$fngroup++;
	} else {
		$fncount++;
		if (preg_match("/#([0-9]+)(?>\\s+(.+))?/s", $fn_str, $fn_str_parts)) {
			$fncount = $fn_str_parts[1];
			$fn_str = $fn_str_parts[2];
		}
		$fnid = $fngroup . '_' . $fncount;
	
		$out = ($fn_str_parts[2] != '' && $fntext[$fncount] == '#') ? '' : "<sup><a class='footnote' id='fnr$fnid' href='#fn$fnid'>[$fncount]</a></sup>";
		global $FootnoteBacklinkCharacter;
		if ($fn_str != '') {
			$fntext[$fncount] = "<div class='footnote' id='fn$fnid'>\n" . 
								"<span class='footnote-number'>$fncount</span> {$fn_str} <a href='#fnr$fnid'>$FootnoteBacklinkCharacter</a>\n" . 
								"</div>";
		} else if ($fntext[$fncount] == '') {
			$fntext[$fncount] = '#';
		}
	}
	return $out;
}
