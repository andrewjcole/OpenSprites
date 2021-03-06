<?php

  // Upload script, puts uploaded files in /uploads/ [scripts|sprites]
  // in file upload form, ScriptSpriteFile file chooser, ScratchVersion text, ScriptSpriteName text, IsScriptSprite text
  
  session_start();
  
  // TODO: make a page that errors are redirected to?
  
  // If you need to test it, comment out the test for if you are logged in
  if(!isset($_SESSION["username"])){
    echo "You cannot upload files if you are not logged in!";
    exit;
  }
  if(!isset($_FILES["ScriptSpriteFile"]) || !isset($_POST["ScratchVersion"]) || !isset($_POST["ScriptSpriteName"]) || !isset($_POST["IsScriptSprite"])){
    echo "Missing info about Script/Sprite";
    exit;
  }
  if($_POST["IsScriptSprite"] !== "Script" && $_POST["IsScriptSprite"] !== "Sprite"){
    echo "Invalid Script/Sprite choice";
    exit;
  }
  // About 10 MB, the scratch site upload limit 
  if($_FILES["ScriptSpriteFile"]["size"] > 10000000){
    echo "File too large!";
    exit;
  }
  // Name file after hash, to prevent attacks
  $ShaHash = (string) sha1_file($_FILES["ScriptSpriteFile"]["tmp_name"]);
  if($_POST["IsScriptSprite"] === "Script"){
    move_uploaded_file( $_FILES["ScriptSpriteFile"]['tmp_name'], "./uploads/scripts/".$ShaHash );
  }else{
    move_uploaded_file( $_FILES["ScriptSpriteFile"]['tmp_name'], "./uploads/sprites/".$ShaHash );
  }
  
  // TODO: add the name & hash to a database
  $hash_file = file_get_contents("./uploads/hashes.txt");
  $hash_file .= "\n".$ShaHash.' = '.$_POST["ScriptSpriteName"].'';
  file_put_contents("./uploads/hashes.txt", $hash_file);
  
  header("Location: /"); // Redirect to main page, can be changed later
?>