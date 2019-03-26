<?php
require_once 'Zexarel/loader.php';
require_once 'classes/View.php';
require_once 'classes/Database.php';

require_once 'classes/EmailController.php';
require_once 'classes/LoginController.php';

ZRoute::get("/", function (){
  redirect("home");
});

ZRoute::get("/home", function (){
  echo View::get("home");
}, "home");

ZRoute::get("/email/<code>/block/<id>", function ($data){
  header('Content-Type: application/json');
  echo json_encode(EmailController::getBlockStatus($data['code'], $data['id']));
});

ZRoute::get("/email/<code>/email/<id>", function ($data){
  header('Content-Type: application/json');
  echo json_encode(EmailController::getEmailStatus($data['code'], $data['id']));
});

ZRoute::get("/email/recive/<code>", function ($data){
  $s = EmailController::destroyToken($data['code']);
  $s = explode("&", $s);
  $r = [];
  foreach($s as $ss){
    $a = explode("=", $ss);
    $r[$a[0]] = $a[1];
  }


});

ZRoute::post("/email/send/<code>", function($data){
  EmailController::send(
    $data['code'],
    isset($data['to']) ? $data['to'] : "",
    isset($data['subject']) ? $data['subject'] : "",
    isset($data['message']) ? $data['message'] : "",
    isset($data['header']) ? $data['header'] : "",
    isset($data['parameter']) ? $data['parameter'] : ""
  );
});

ZRoute::listen();

?>
