<?php
class EmailController{

  public static function send($to, $subject, $message, $header = null, $parameter = null){
    d_var_dump(func_get_args());
    $to = explode(",", $to);
    $DB = new Database();
    $DB->insert("emailblock", "idUtente", "subject", "message", "header", "parameter")
      ->value(1, $subject, $message, $header, $parameter)
      ->execute();
      $id = $DB->select("MAX(id) AS is")
        ->from("emailblock")
        ->execute();
      $id = $id[0]['id'];

  }

  public static function getBlockStatus($token, $id){
    $return = [];
    $DB = new Database();
    $ret = $DB->select("id")
      ->from("utenti")
      ->where("emailToken", "=", $token)
      ->execute();
    if(sizeof($ret) == 1){
      $return = $DB->select("*")
        ->from("emailblock")
        ->where("id", "=", $id)
        ->execute();
    }
    return $return;
  }

  public static function getEmailStatus($token, $id){
    $return = [];
    $DB = new Database();
    $ret = $DB->select("id")
      ->from("utenti")
      ->where("emailToken", "=", $token)
      ->execute();
    if(sizeof($ret) == 1){
      $return = EmailController::getEmail($id);
    }
    return $return;
  }

  private static function getEmail($id){
    $DB = new Database();
    $ret = $DB->select("*")
      ->from("email")
      ->where("id", "=", $id)
      ->execute();
    return isset($ret[0]) ? $ret[0] : [];
  }

}
