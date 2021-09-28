<?php


class User
{
    private $_db,
    $_data,
    $_session_name,
    $_cookie_name;
    public $isLoggedIn = false;

    public function __construct($user = null) {
        $this->_db = DB::getInstance();
        $this->_session_name = Config::get('session/session_name');
        $this->_cookie_name = Config::get('remember/cookie_name');
        if(!$user) {
            if(Session::exists($this->_session_name)) {
                if($this->find(Session::get($this->_session_name))) {
                    $this->isLoggedIn = true;
                } else {
                    //LOGOUT
                }
            }
        } else {
            $this->find($user);
        }
    }

    public function add($fields = array()) { //Ajouter aux utilisateurs le nouvel utilisateur avec les champs
        if(!$this->_db->insert('users', $fields)) {
            throw new Exception("Problem ???");
        }
    }

    public function update($fields = array()) { //Update
        if(!$this->_db->update('users',$this->data()->id, $fields)) {
            throw new Exception("Problem ???");
        }
    }

    private function find($user = null) { //Cherche dans la base de données et stocke les données dans l'objet User
    if($user) {
        $field = is_numeric($user) ? 'id' : 'username';
    }
    $data = $this->_db->get('users', array($field, '=', $user));

    if($data->getCount() == 1) {
        $this->_data = $data->getFirst();
        return true;
    }

    return false;
}

    public function login($username, $password, $remember) { //Gère les logins et met en place la session et les éventuels cookies

        if($this->find($username)) {
            if($this->_data->password == Hash::make(Input::get('password'), $this->_data->salt)) {
                Session::insert($this->_session_name, $this->_data->id);
                if($remember) {
                    if($this->_db->get('users_session', array('user_id', '=', $this->_data->id))->getCount() != 1) {
                        echo "here";
                        $hash = Hash::unique();
                        $this->_db->insert('users_session', array('user_id' => $this->_data->id, 'hash' => $hash));
                        Cookie::add($this->_cookie_name, $hash, Config::get('remember/cookie_expiry'));
                    } else {

                    }
                }
                return true;
            } else {
                throw new Exception("<p id=\"errorLogin\">Wrong password.</p>");
                return false;
            }
        } else {
            throw new Exception("<p id=\"errorLogin\">Wrong username.</p>");
            return false;
        }

        return false;
    }

    public function cookieLogin() { //Login automatique si on a choisi le site de se rappeller de nous
        if(!empty($this->_data)) {
            Session::insert($this->_session_name, $this->_data->id);
        }
    }

    public function isLoggedIn() { 
        return $this->isLoggedIn;
    }

    public function data() {
        return $this->_data;
    }

    public function logout() { //Gère les Déconnexions
        Session::delete(Config::get('session/session_name'));
        Session::flash('disconnect', 'You have just disconnected. See you soon !');
        $this->isLoggedIn = false;
        $this->_db->delete('users_session', array('user_id', '=', $this->_data->id));
        Cookie::delete($this->_cookie_name);
    }

    public function delete() { //Supprimer un compte.
        Session::delete(Config::get('session/session_name'));
        $this->isLoggedIn = false;
        $id = $this->_data->id;
        $this->_db->delete('users_session', array('user_id', '=', $this->_data->id));
        Cookie::delete($this->_cookie_name);
        $this->_db->delete('users', array("id", "=", $id));
    }
    
    public function hasPermission($perm) {

        $group = $this->_db->get("groups", array("id", "=", $this->data()->gid));
        $permission = json_decode($group->getFirst()->permissions, true);
        if($permission[$perm] == 1) {
            return true;
        }

        return false;
    }

    public function getPermission() {
        if($this->hasPermission("admin")) {
            return "Administrateur";
        }
        else if($this->hasPermission("redactor")) {
            return "Rédacteur";
        }
        else {
            return "Utilisateur";
        }
    }
}