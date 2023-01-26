<?php

namespace visor3d\database;

class DatabaseManager {
    static function createDatabase() {
        $stm = 'CREATE DATABASE `visor3d`';

        $pdo = new PDO('mysql:host=localhost', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pdo->exec($stm);
        if ($pdo === false) {
            die('Failed to create database');
        }
    }

    static function createTables() {

    }

    private static function createTablaUsuario() {
        
    }

    private static function createTablaPerfil() {

        
    }

    private static function createTablaModelo() {
        
    }
}
