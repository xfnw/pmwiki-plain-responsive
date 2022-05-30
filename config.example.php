<?php
$WikiTitle = "example";

$DefaultPasswords['admin'] = pmcrypt('hackme');

$EnableUpload = 1;
$DefaultPasswords['upload'] = pmcrypt('hackme');

$ScriptUrl = 'https://example.com/wiki/';

$Skin = "pmwiki-plain-responsive";

$DefaultGroup = 'Example';

include_once("scripts/xlpage-utf-8.php");

$EnableBlocklist = 10;

if ($action == 'rss')  include_once("scripts/feeds.php");
if ($action == 'atom') include_once("scripts/feeds.php");

$BaseNamePatterns['/(-Talk|-Users)$/i'] = '';

$PmTOC['Enable'] = 1;

include_once("$FarmD/cookbook/footnote2.php");
include_once("$FarmD/scripts/refcount.php");

