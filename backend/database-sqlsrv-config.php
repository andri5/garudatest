<?php

/**
 * Konfigurasi Database Multiple untuk Laravel
 * 
 * Tambahkan konfigurasi ini ke file config/database.php
 * di bagian 'connections' array
 * 
 * Konfigurasi ini mendukung:
 * - 1 MySQL (default)
 * - 2 SQL Server (sqlsrv dan sqlsrv2)
 */

return [
    // MySQL - Default Connection
    'mysql' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'forge'),
        'username' => env('DB_USERNAME', 'forge'),
        'password' => env('DB_PASSWORD', ''),
        'unix_socket' => env('DB_SOCKET', ''),
        'charset' => env('DB_CHARSET', 'utf8mb4'),
        'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => null,
        'options' => extension_loaded('pdo_mysql') ? array_filter([
            PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        ]) : [],
    ],

    // SQL Server 1
    'sqlsrv' => [
        'driver' => 'sqlsrv',
        'host' => env('SQLSRV_HOST', 'localhost'),
        'port' => env('SQLSRV_PORT', '1433'),
        'database' => env('SQLSRV_DATABASE', 'forge'),
        'username' => env('SQLSRV_USERNAME', 'forge'),
        'password' => env('SQLSRV_PASSWORD', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'encrypt' => env('DB_ENCRYPT', 'yes'),
        'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
    ],

    // SQL Server 2
    'sqlsrv2' => [
        'driver' => 'sqlsrv',
        'host' => env('SQLSRV2_HOST', 'localhost'),
        'port' => env('SQLSRV2_PORT', '1433'),
        'database' => env('SQLSRV2_DATABASE', 'forge'),
        'username' => env('SQLSRV2_USERNAME', 'forge'),
        'password' => env('SQLSRV2_PASSWORD', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'encrypt' => env('DB_ENCRYPT', 'yes'),
        'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
    ],
];

