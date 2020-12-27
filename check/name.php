<?php
require "../common.php";
setContentType();
setSessionSavePath();
session_start();
if (!isset($_POST['value'])) {
    http_response_code(403);
    return;
}

$db = &DB::getInstance();
if ($db->nameExists($_POST['value'])) {
    header("Status: 422 Unprocessable Entity");
    echo "该用户名已存在！";
}
