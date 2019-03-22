<?php
class EmailController{

  public static function send($token, $to, $subject, $message, $header = null, $parameter = null){
    $l = LoginController::checkToken($token);
    if($l != false){
      $to = EmailController::prepareTo($to);
      $DB = new Database();
      $DB->insert("emailblock", "idUtente", "subject", "message", "header", "parameter")
        ->value(1, $subject, $message, $header, $parameter)
        ->execute();
      $id = $DB->select("MAX(id) AS id")
        ->from("emailblock")
        ->execute();
      $id = intval($id[0]['id']);
      $header = EmailController::prepareHeader($header);
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

  private static function prepareTo($to){
    if(!is_array($to)){
      $to = explode(",", $to);
    }
    return $to;
  }
  private static function prepareHeader($header){
    if(!is_array($header)){
      $header = explode("\r\n", $header);
    }
    $header['Content-type'] = "text/html; charset=iso-8859-1";
    return implode("\r\n", $header);
  }
  private static function isHTML($string){
    return $string != strip_tags($string);
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
