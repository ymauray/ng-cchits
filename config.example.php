<?php

/**
 * The database configuration. See http://medoo.in/api/new for detailed information.
 */
$db_config = [
    // Required
    'database_type' => 'mysql',
    'database_name' => 'mydb',
    'server' => 'localhost',
    'username' => 'myuser',
    'password' => 'mypassword',
    'charset' => 'utf8',

    // Optional port
    'port' => 3306,

    // Optional table prefix
    'prefix' => '_PREFIX',

    // Optional additional information
    'option' => [
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ]
];
