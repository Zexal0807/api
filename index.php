<?php
require_once 'Zexarel/loader.php';

ZRoute::get("/", function (){
  redirect("home");
});

ZRoute::get("/home", function (){
  echo "Questa è la route della homepage";
}, "home");

ZRoute::listen();

?>
