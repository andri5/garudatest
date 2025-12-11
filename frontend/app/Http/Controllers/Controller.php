<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Database connection untuk connection selain MySQL
     * MySQL adalah connection utama (default), tidak perlu didefinisikan
     * Hanya connection lain yang perlu didefinisikan di sini
     */
    public static $pandawa = 'pandawa';
    public static $sqlsrv_seleksi = 'sqlsrv_seleksi';
}
