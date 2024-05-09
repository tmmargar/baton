<?php
declare(strict_types = 1);
namespace ccp;
require_once "init.php";
$smarty->assign("title", "Twirling for Grace Change Log");
$smarty->assign("heading", "");
$smarty->assign("style", "");
$outputChange =
  "<h1>Change Log</h1>\n" .
  "<section class=\"version\" id=\"1.0.1\">" .
  " <h3>Version 1.0.1</h3>\n" .
  " <b><time datetime=\"2024-5-7\">May 7, 2024</time></b>\n" .
  " <ul>\n" .
  "  <li>Administration of teams</li>\n" .
  " </ul>\n" .
  "</section>\n" .
  "<section class=\"version\" id=\"1.0.0\">" .
  " <h3>Version 1.0.0</h3>\n" .
  " <b><time datetime=\"2024-5-1\">May 1, 2024</time></b>\n" .
  " <ul>\n" .
  "  <li>Member signup and approval</li>\n" .
  "  <li>Administration including management of invoices, events and event types</li>\n" .
  "  <li>Calendar</li>\n" .
  " </ul>\n" .
  "</section>\n";
$smarty->assign("content", $outputChange);
$smarty->display("changeLog.tpl");