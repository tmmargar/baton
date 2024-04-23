<?php
use Baton\T4g\Model\Constant;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use DoctrineExtensions\Query\Mysql\ConcatWs;
use DoctrineExtensions\Query\Mysql\DateAdd;
use DoctrineExtensions\Query\Mysql\IfNull;
use DoctrineExtensions\Query\Mysql\Round;
use DoctrineExtensions\Query\Mysql\Year;
use DoctrineExtensions\Query\Mysql\SubstringIndex;
require_once "vendor/autoload.php";
function getEntityManager() : EntityManager
{
    $entityManager = null;
    if ($entityManager === null)
    {
        $config = ORMSetup::createAttributeMetadataConfiguration(paths: array("src"), isDevMode: true);
        $config->addCustomDateTimeFunction('CONCAT_WS', ConcatWs::class);
        $config->addCustomDateTimeFunction('DATEADD', DateAdd::class);
        $config->addCustomDateTimeFunction('IFNULL', IfNull::class);
        $config->addCustomDateTimeFunction('ROUND', Round::class);
        $config->addCustomStringFunction('SUBSTRING_INDEX', SubstringIndex::class);
        $config->addCustomDateTimeFunction('YEAR', Year::class);
        if ($_SERVER["SERVER_NAME"] == Constant::URL) {
            $username = Constant::DATABASE_USER_NAME_SERVER;
            $password = Constant::DATABASE_PASSWORD_SERVER;
        } else {
            $username = Constant::DATABASE_USER_NAME_LOCAL;
            $password = Constant::DATABASE_PASSWORD_LOCAL;
        }
        $connection = DriverManager::getConnection(params: [ 'dbname' => Constant::DATABASE_NAME, 'user' => $username, 'password' => $password, 'host' => Constant::DATABASE_HOST_NAME, 'driver' => 'pdo_mysql'], config: $config);
        $entityManager = new EntityManager(conn: $connection, config: $config);
    }
    return $entityManager;
}