<?php
header("Content-type: text/html; charset=utf-8");
if (!isset($_POST['value'])) {
    http_response_code(403);
    return;
}

require '../DB.php';
$db = &DB::getInstance();
if ($db->studentIDExists($_POST['value'])) {
    header("Status: 422 Unprocessable Entity");
    echo "该学号已存在！";
}
