<?php
require_once 'Zexarel/loader.php';

require_once 'classes/Database.php';

require_once 'classes/EmailController.php';

ZRoute::get("/", function (){
  redirect("home");
});

ZRoute::get("/home", function (){
  echo "Questa è la route della homepage";
}, "home");

ZRoute::get("/email/<code>/block/<id>", function ($data){
  d_var_dump($data);
  header('Content-Type: application/json');
  echo json_encode(EmailController::getBlockStatus($data['<code>'], $data['<id>']));
});

ZRoute::get("/email/<code>/email/<id>", function ($data){
  header('Content-Type: application/json');
  echo json_encode(EmailController::getEmailStatus($data['<code>'], $data['<id>']));
});

ZRoute::post("/test", function($data){
  $ch = curl_init();
  // define options
  $optArray = array(
    CURLOPT_POST => 1,
    CURLOPT_HEADER => 0,
    CURLOPT_URL => 'http://www.serverserver.com',
    CURLOPT_FRESH_CONNECT => 1,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_FORBID_REUSE => 1,
    CURLOPT_TIMEOUT => 4,
    CURLOPT_POSTFIELDS => http_build_query($post)
  );
  // apply those options
  curl_setopt_array($ch, $optArray);
  // execute request and get response
  $result = curl_exec($ch);
  d_var_dump($result);
});

ZRoute::listen();

?>
