<?php

//Classe auxilliaire vérifiant seulement si les input existent et récupère les données
//TOUTES les requêtes SQL sont accompagnés d'un prepare donc protégé des injections SQL.
class Input
{

    public static function get($field) {
        if(isset($_POST[$field])) {
            return escape($_POST[$field]);
        } else {
            return false;
        }
    }

    public static function exists() {
        return !empty($_POST);
    }

}