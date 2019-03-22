<?php
class EmailController{

  public static function send($token, $to, $subject, $message, $header = null, $parameter = null){
    $l = LoginController::checkToken($token);
    if($l != false){
      $to = explode(",", $to);
      $DB = new Database();
      $DB->insert("emailblock", "idUtente", "subject", "message", "header", "parameter")
        ->value(1, $subject, $message, $header, $parameter)
        ->execute();
      $id = $DB->select("MAX(id) AS id")
        ->from("emailblock")
        ->execute();
      $id = intval($id[0]['id']);
      foreach($to as $t){
        error_reporting(0);
        $s = mail($t, $subject, $message, $header, $parameter);
        error_reporting(E_ALL);
        $DB->insert("email", "idEmailBlock", "reciver", "invio", "status")
          ->value($id, $t, ($s ? date("Y-m-d H:i:s") : null), ($s ? "Success" : error_get_last()['message']))
          ->execute();
      }
    }
  }

  public static function getBlockStatus($token, $id){
    $return = [];
    $l = LoginController::checkToken($token);
    if($l != false){
      $return = $DB->select("*")
        ->from("emailblock")
        ->where("id", "=", $l)
        ->execute();
    }
    return $return;
  }

  public static function getEmailStatus($token, $id){
    $return = [];
    $l = LoginController::checkToken($token);
    if($l != false){
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
