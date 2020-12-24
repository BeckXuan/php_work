<?php
require "common.php";
setContentType();
session_start();
if (isAdminLegal()) {
    jumpToAdminIndex();
    return;
}
if (!isset($_POST['account'], $_POST['password'])) {
    jumpToAdminLogin();
    return;
}
$password = $_POST['password'];
if (!preg_match("/^[a-z0-9]{32}$/", $password)) {
    http_response_code(403);
    return;
}
$account = $_POST['account'];
$message = '';
require 'DB.php';
$db = &DB::getInstance();
require "admin_config.php";
/* @noinspection PhpUndefinedVariableInspection */
if ($adminAccount !== $account || $adminPassword !== $password) {
    $message = '账号或密码错误！';
} else {
    /* @noinspection PhpUndefinedVariableInspection */
    $name = $adminName;
    $_SESSION['adminName'] = $name;
    $_SESSION['adminAccount'] = $account;
    if (isset($_POST['rem']) && $_POST['rem'] === '1') {
        setcookie('adminName', $name, time() + 3600);
        setcookie('adminAccount', $account, time() + 3600);
    } else {
        setcookie('adminName', $name);
        setcookie('adminAccount', $account);
    }
    jumpToAdminIndex();
    return;
}
header("Status: 422 Unprocessable Entity");
echo $message;
