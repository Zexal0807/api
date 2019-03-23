<?php
require_once 'Zexarel/loader.php';

require_once 'classes/Database.php';

require_once 'classes/EmailController.php';
require_once 'classes/LoginController.php';

ZRoute::get("/", function (){
  redirect("home");
});

ZRoute::get("/home", function (){
  echo "Questa è la route della homepage";
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
  var_dump($data);
  var_dump(EmailController::destroyToken($data['code']));
});

ZRoute::post("/send/<code>", function($data){
  //check the code
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
