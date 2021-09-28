<?php
include_once "Core/init.php";

$user = new User();
$user->delete();
Session::flash('deleted', 'Your account was deleted');
Redirect::to('index');
