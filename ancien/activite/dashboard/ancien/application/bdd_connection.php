<?php
session_start();
try{
    $dbhost = 'localhost';
    $dbname = 'bdd_projet-l3an1';
    $dbuser = 'root';
    $dbpass = '';
    $pdo = new PDO("mysql:host={$dbhost};dbname={$dbname}",$dbuser,$dbpass);
    $pdo->exec("set names utf8");
}catch(PDOException $ex){
    die($ex->getMessage());
}