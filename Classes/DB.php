<?php


class DB
{
    private static $_instance = null;
    private
    $_pdo,
    $_query,
    $_error = false,
    $_results,
    $_count = 0;

    //Construire la base de données
    private function __construct() {
        try {
            $this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/name'),
                Config::get('mysql/username'),
                Config::get('mysql/password')
            );
        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }

    //Retourne une instance de la base de données (objet unique)
    public static function getInstance() {
        if(DB::$_instance == null) {
            DB::$_instance = new DB();
            return DB::$_instance;
        }
        return DB::$_instance;
    }

    //Performe une requête SQL en fonctions de paramètres avec prepare (paramètres en ?)
    public function query($sql, $params = array()) {
        $this->_error = false;
        if($this->_query = $this->_pdo->prepare($sql)) {
            if(count($params)) {
                $x = 1;
                foreach ($params as $param) {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }
            if($this->_query->execute()) {
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            } else {
                $this->_error = true;
            }
        }
        return $this;
    }

    //Copie de DB::action qui est utilisée pour ajouter ou update et ne stocke pas les résultats.
    public function insertUpdateQuery($sql, $params = array()) {
        $this->_error = false;
        if($this->_query = $this->_pdo->prepare($sql)) {
            if(count($params)) {
                $x = 1;
                foreach ($params as $param) {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }
            if($this->_query->execute()) {

            } else {
                $this->_error = true;
            }
        }
        return $this;
    }

    //Rend la requête SQL en language naturel | plus facile d'utilisation
    private function action($action, $table, $conditions = array()) {
        if(count($conditions) == 3) {
            $operators = array('=', '<', '>', '<=', '>=', '!=');
            $field1 = $conditions[0];
            $operator = $conditions[1];
            $field2 = $conditions[2];
            if(in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field1} {$operator} ?";
                $this->query($sql, array($field2));
            } else {
                $this->_error = true;
            }
        } else {
            $this->_error = true;
        }
        return $this;

    }

    private function action1($action, $table, $conditions = array()) {
        if(count($conditions) == 3) {
            $operators = array('=', '<', '>', '<=', '>=');
            $field1 = $conditions[0];
            $operator = $conditions[1];
            $field2 = $conditions[2];
            if(in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field1} {$operator} ?";
                $this->insertUpdateQuery($sql, array($field2));
            } else {
                $this->_error = true;
            }
        } else {
            $this->_error = true;
        }
        return $this;

    }

    //Utilise action pour faire des requêtes sql
    public function get($table, $conditions = array()) {
        return $this->action('SELECT *', $table, $conditions);
    }

    //Utilise action pour faire des requêtes sql
    public function delete($table, $conditions = array()) {
        return $this->action1('DELETE', $table, $conditions);
    }

    //Utilise insertUpdateQuery pour faire des requêtes sql
    public function insert($table, $sentFields = array()) {
        $sentKeys = array_keys($sentFields);
        $keys = "";
        $fields = "";
        $c = 1;
        $c1 = 1;
        foreach ($sentKeys as $key) {
            $keys .= $key;
            if($c != count($sentKeys)) {
                $keys .= "`, `";
            }
            $c++;
        }
        foreach ($sentFields as $field) {
            $fields .= "?";
            if($c1 != count($sentFields)) {
                $fields .= ", ";
            }
            $c1++;
        }
        $sql = "INSERT INTO {$table} (`{$keys}`) VALUES ({$fields})";
        if (!$this->insertUpdateQuery($sql, $sentFields)->getError()) {
            return true;
        }
        return false;
    }

    //Insere une carte avec la face devant et derriere
    public function insertCard($table, $sentFields = array()) {
        $sentKeys = array_keys($sentFields);
        $keys = "";
        $fields = "";
        $c = 1;
        $c1 = 1;
        foreach ($sentKeys as $key) {
            $keys .= $key;
            if($c != count($sentKeys)) {
                $keys .= "`, `";
            }
            $c++;
        }
        foreach ($sentFields as $field) {
            $fields .= "?";
            if($c1 != count($sentFields)) {
                $fields .= ", ";
            }
            $c1++;
        }
        $sql = "INSERT INTO {$table} (`{$keys}`) VALUES ({$fields})";
        if($this->get($table,
            array(
                "cardFront",
                "=",
                $sentFields['cardFront']))
            ->getCount() ==0) {
            if (!$this->insertUpdateQuery($sql, $sentFields)->getError()) {
                return true;
            }
        }
        else {
            die("Carte existe déja");
        }
        return false;
    }

    //Update data
    public function update($table, $id, $sentParams = array()) {

        $sentKeys = array_keys($sentParams);
        $keys = "";
        $c = 1;
        foreach ($sentKeys as $key) {
            $keys .= $key;
            if(count($sentKeys) != $c) {
                $keys .= " = ?, ";
            }
            else {
                $keys .= " = ?";
            }
            $c++;
        }

        $sql = "UPDATE {$table} SET {$keys} WHERE id = {$id}";
        if($this->insertUpdateQuery($sql, $sentParams)->getError()) {
            return false;
        }
        else {
            return true;
        }
    }
    
    //Update card data
    public function updateCard($table, $id, $all, $sentParams = array()) {

        $sentKeys = array_keys($sentParams);
        $keys = "";
        $c = 1;
        foreach ($sentKeys as $key) {
            $keys .= $key;
            if(count($sentKeys) != $c) {
                $keys .= " = ?, ";
            }
            else {
                $keys .= " = ?";
            }
            $c++;
        }
        // $all is useful to update all cards
        $sql = "UPDATE {$table} SET {$keys} WHERE cardGroup = {$id} AND cardOrder = {$all}";
        if($this->insertUpdateQuery($sql, $sentParams)->getError()) {
            return false;
        }
        else {
            return true;
        }
    }
    
     // Check si une carte des questions existe déja
    public function checkCard($table, $sentFields = array()) {
        if($this->get($table,
            array(
                "cardFront",
                "=",
                $sentFields['cardFront']))
            ->getCount() ==0) {
            return true;
        }
        else {
        }
        return false;
    }

    // Insere le nom et categorie d'un paquet de carte
    public function insertGroup($table, $sentFields = array()) {
        $sentKeys = array_keys($sentFields);
        $keys = "";
        $fields = "";
        $c = 1;
        $c1 = 1;
        foreach ($sentKeys as $key) {
            $keys .= $key;
            if($c != count($sentKeys)) {
                $keys .= "`, `";
            }
            $c++;
        }
        foreach ($sentFields as $field) {
            $fields .= "?";
            if($c1 != count($sentFields)) {
                $fields .= ", ";
            }
            $c1++;
        }
        $sql = "INSERT INTO {$table} (`{$keys}`) VALUES ({$fields})";
        if($this->get($table,
            array(
                "name",
                "=",
                $sentFields['name']))
            ->getCount() ==0) {
            if (!$this->insertUpdateQuery($sql, $sentFields)->getError()) {
                return true;
            }
        }
        else {
            die("<div class=\"errorExist\">Choose a unique name</div>");
        }
        return false;
    }
    
    //Permet de créer une nouvelle catégorie
    public function insertCategory($table, $sentFields = array()) {
        $sentKeys = array_keys($sentFields);
        $keys = "";
        $fields = "";
        $c = 1;
        $c1 = 1;
        foreach ($sentKeys as $key) {
            $keys .= $key;
            if($c != count($sentKeys)) {
                $keys .= "`, `";
            }
            $c++;
        }
        foreach ($sentFields as $field) {
            $fields .= "?";
            if($c1 != count($sentFields)) {
                $fields .= ", ";
            }
            $c1++;
        }
        $sql = "INSERT INTO {$table} (`{$keys}`) VALUES ({$fields})";
        if($this->get($table,
            array(
                "catname",
                "=",
                $sentFields['catname']))
            ->getCount() ==0) {
            if (!$this->insertUpdateQuery($sql, $sentFields)->getError()) {
                return true;
            }
        }
        else {
            die("<div class=\"errorExist\">Create a new category</div>");
        }
        return false;
    }

    public function getResults() {
        return $this->_results;
    }

    public function getFirst() {
        return $this->getResults()[0];
    }

    public function getError() {
        return $this->_error;
    }

    public function getCount() {
        return $this->_count;
    }


}