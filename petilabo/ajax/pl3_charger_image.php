<?php
header('Content-type: application/json');
$name = isset($_POST["name"])?$_POST["name"]:"img";

$cible = "../../xml/images/upload-".$name.".png";
move_uploaded_file($_FILES[$name]['tmp_name'], $cible);

echo json_encode(array("code" => 0, "url" => $cible));