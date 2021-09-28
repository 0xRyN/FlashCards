<?php

Class CategoryGroup {

    public static function getCategory() {
        $db = DB::getInstance();
        if(!empty($res = $db->query("SELECT * FROM category ORDER BY id ASC")->getResults())) {
            foreach($res as $a) {
                self::addGroup($a->catname);
            }
        }
    }

    private static function addGroup($name) { //affiche les categories
        echo "<div class=\"indexCard\">
        <p>$name</p>
        <span></span>
        <div class=\"card-placeholder\">";
        GamesGroup::getGroups($name);
        echo "
        </div>
        </div>";
    }
    
}
?>