<?php
if (!isset($_POST['value'])) {
    http_response_code(403);
    return;
}

require '../DB.php';
$db = &DB::getInstance();
if ($db->nameExists($_POST['value'])) {
    header("Status: 422 Unprocessable Entity");
    echo "该用户名已存在！";
}
