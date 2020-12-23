<?php
header("Content-type: text/html; charset=utf-8");
session_start();

if (isset($_SESSION['studentID'])) {
    header('location: content.php');
    return;
}

if (!isset($_POST['studentID'], $_POST['password'])) {
    header('location: login.php');
    return;
}

//引入DB.php
require 'DB.php';

//登录处理
//获取参数
$password = $_POST['password'];
if (!preg_match("/^[a-z0-9]{32}$/", $password)) {
    http_response_code(403);
    return;
}
$studentID = $_POST['studentID'];
$message = '';
$db = &DB::getInstance();
if (!$db->studentIDExists($studentID)) {
    $message = '该学号不存在！';
} else if (!($db->getUserPassword($studentID) === $password)) {
    $message = '密码错误！';
} else if (!$db->isUserAccessible($studentID)) {
    $message = '请等待管理员审核后登录！';
} else {
    //写入Session、cookie，并转到内容界面
    $name = $db->getUserName($studentID);
    $_SESSION['name'] = $name;
    $_SESSION['studentID'] = $studentID;
    if (isset($_POST['rem']) && $_POST['rem'] === '1') {
        setcookie('name', $name, time() + 3600);
        setcookie('studentID', $studentID, time() + 3600);
    } else {
        setcookie('name', $name);
        setcookie('studentID', $studentID);
    }
    return;
}
header("Status: 422 Unprocessable Entity");
echo $message;
