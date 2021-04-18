<?php
//Demarrage de la session utilisateur
session_start();

$pdo = new PDO
    (
        //connexion avec la base de donnees
        'mysql:host=localhost;dbname=bdd_projet-l3an1;charset=UTF8',
        'root',
        ''
    );

