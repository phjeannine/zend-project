<?php
return array('doctrine' => array(
    'connection' => array (
        'orm_default' => array(
            'driverClass' => 'Doctrine\DBAL\Driver\PDOMysql\Driver',
            'params' => array(
                'host' => 'lacuisinedesreves.dev.com:8888/',
                'port' => '3306',
                'user' => 'root',
                'password' => 'root',
                'dbname' => 'cuisinedesreves',
                'driver_options' => array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
                ),
)))));