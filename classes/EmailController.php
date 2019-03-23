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

  private static function createToken($block, $reciver){
    $char = [
			"q", "r", "h", "a", "t", "8", "g", "m", "f",
      "n", "o", "y", "k", "5", "2", "3", "e", "v",
      "w", "d", "7", "0", "4", "1", "u", "6", "i",
      "l", "p", "c", "j", "x", "z", "s", "9", "b"
		];
    $t = "block=".$block."&reciver=".strtolower($reciver);
    $t = str_replace([".", "=", "&", "@"], ["zpuntoz", "zugualez", "zecomz", "zchiocz"], $t);
    $c = intval(substr(date("U"), -1));
    if($c == 0){
      $c = 1;
    }
    $ret = "";
    for($i = 0; $i < strlen($t); $i++){
      $f = array_search(substr($t, $i, 1), $char) + $c;
      if($f > 35){
        $f = $f - 36;
      }
      $ret .= $char[$f];
    }
    return $c.$ret;
  }
  public static function destroyToken($token){
    $char = [
      "q", "r", "h", "a", "t", "8", "g", "m", "f",
      "n", "o", "y", "k", "5", "2", "3", "e", "v",
      "w", "d", "7", "0", "4", "1", "u", "6", "i",
      "l", "p", "c", "j", "x", "z", "s", "9", "b"
    ];
    $c = intval(substr($token, 0, 1));
    $ret = "";
    for($i = 1; $i < strlen($token); $i++){
      $f = array_search(substr($token, $i, 1), $char) - $c;
      if($f < 0){
        $f = $f + 36;
      }
      $ret .= $char[$f];
    }
    $ret = str_replace(["zpuntoz", "zugualez", "zecomz", "zchiocz"], [".", "=", "&", "@"], $ret);
    return $ret;
  }
  private static function prepareHeader($header){
    if(!is_array($header) && strlen($header) > 0){
      $header = explode("\r\n", $header);
    }
    $header['Content-type'] = "text/html; charset=iso-8859-1";
    $h = [];
    foreach ($header as $k => $v) {
      $h[] = $k.":".$v;
    }
    return implode("\r\n", $h);
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
