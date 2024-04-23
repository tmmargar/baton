<?php
declare(strict_types = 1);
namespace Baton\T4g;
use Baton\T4g\Model\SmartyLocal;
require_once "vendor/autoload.php";
$smartyCcp = new SmartyLocal();
$smartyCcp->initialize(debug: false);
// variable used in individual pages
$smarty = $smartyCcp->getSmarty();