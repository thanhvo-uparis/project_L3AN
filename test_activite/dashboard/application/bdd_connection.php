<?php
session_start();
$pdo = new PDO
    (
        //serveur
        'mysql:host=localhost;dbname=bdd_projet-l3an1;charset=UTF8',
        'root',
        ''
    );
?>