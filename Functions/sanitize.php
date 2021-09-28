<?php

function escape($string) { //Echappe les charactères HTML 
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}
