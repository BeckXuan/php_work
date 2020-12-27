<?php
require "../common.php";
setContentType();
setSessionSavePath();
session_start();
if (isAdminLegal()) {
    header('location: ../admin/index.php');
    return;
}
if (!isset($_POST['account'], $_POST['password'])) {
    header('location: ../admin/login.php');
    return;
}
$password = $_POST['password'];
if (!preg_match("/^[a-z0-9]{32}$/", $password)) {
    http_response_code(403);
    return;
}
$account = $_POST['account'];
$message = '';
$db = &DB::getInstance();
require "../config/admin.php";
/* @noinspection PhpUndefinedVariableInspection */
if ($adminAccount !== $account || $adminPassword !== $password) {
    $message = '账号或密码错误！';
} else {
    /* @noinspection PhpUndefinedVariableInspection */
    $name = $adminName;
    $_SESSION['adminName'] = $name;
    $_SESSION['adminAccount'] = $account;
    if (isset($_POST['rem']) && $_POST['rem'] === '1') {
        setcookie('adminName', $name, time() + 3600, '/', '', false, true);
        setcookie('adminAccount', $account, time() + 3600, '/', '', false, true);
    } else {
        setcookie('adminName', $name, 0, '/', '', false, true);
        setcookie('adminAccount', $account, 0, '/', '', false, true);
    }
    return;
}
header("Status: 422 Unprocessable Entity");
echo $message;
