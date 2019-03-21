<?php
class EmailController{


  public function getEmailStatus($token, $id){
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

  private function getEmail($id){
    $DB = new Database();
    $ret = $DB->select("*")
      ->from("email")
      ->where("id", "=", $id)
      ->execute();
    return isset($ret[0]) ? $ret[0] : [];
  }

}
