<?php
if (!isset($_POST['name'], $_POST['studentID'], $_POST['password'])) {
    http_response_code(403);
    return;
}
$password = $_POST['password'];
if (!preg_match("/^[a-z0-9]{32}$/", $password)) {
    http_response_code(403);
    return;
}
$name = $_POST['name'];
$studentID = $_POST['studentID'];
require "DB.php";
$db = &DB::getInstance();

if ($db->nameExists($name) || $db->studentIDExists($studentID)) {
    header("Status: 422 Unprocessable Entity");
    echo '用户名或学号已存在！';
} else if (!$db->addUser($name, $studentID, $password, false)) {
    header("Status: 422 Unprocessable Entity");
    echo '注册失败！';
}
