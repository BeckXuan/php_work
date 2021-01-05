<?php
require "../common.php";
setContentType();
session_start();
if (!isset($_POST['value'])) {
    http_response_code(400);
    return;
}

$db = &DB::getInstance();
if ($db->nameExists($_POST['value'])) {
    http_response_code(406);
    echo "该用户名已存在！";
}
