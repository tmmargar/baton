<?php
use Baton\T4g\Model\Constant;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
require_once "vendor/autoload.php";
$config = ORMSetup::createAttributeMetadataConfiguration(paths: array("src"), isDevMode: true);
if ($_SERVER["SERVER_NAME"] == Constant::URL) {
    $username = Constant::DATABASE_USER_NAME_SERVER;
    $password = Constant::DATABASE_PASSWORD_SERVER;
} else {
    $username = Constant::DATABASE_USER_NAME_LOCAL;
    $password = Constant::DATABASE_PASSWORD_LOCAL;
}
$connection = DriverManager::getConnection(params: [ 'dbname' => Constant::DATABASE_NAME, 'user' => $username, 'password' => $password, 'host' => Constant::DATABASE_HOST_NAME, 'driver' => 'pdo_mysql'], config: $config);
$entityManager = new EntityManager($connection, $config);