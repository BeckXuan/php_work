<?php
require "../common.php";
setContentType();
session_start();
if (!isset($_POST['value'])) {
    http_response_code(403);
    return;
}
$db = &DB::getInstance();
if ($db->studentIDExists($_POST['value'])) {
    header("Status: 422 Unprocessable Entity");
    echo "该学号已存在！";
}
