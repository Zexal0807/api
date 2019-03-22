<?php
class LoginController{

  public function getToken($username, $password){

  }

  public static function checkToken($token){
    $DB = new Database();
    $ret = $DB->select("id")
      ->from("utenti")
      ->where("emailToken", "=", $token)
      ->execute();
    if(sizeof($ret) == 1){
      return $ret[0]['id'];
    }
    return false;
  }

}
