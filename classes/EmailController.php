<?php
class EmailController{


  public static function send($to, $obj, $body, $header = null, $extra = null){
    $to = explode(":", $to);

    $DB = new Database();
    $DB->insert("block", "obj", "body", "header", "extra")
      ->value($obj, $body, $header, $extra)
      ->execute();

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
