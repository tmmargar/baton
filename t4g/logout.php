<?php
declare(strict_types = 1);
namespace Baton\T4g;
use Baton\T4g\Utility\SessionUtility;
require_once "init.php";
echo SessionUtility::destroy();
header("Location:login.php?" . $_SERVER["QUERY_STRING"]);