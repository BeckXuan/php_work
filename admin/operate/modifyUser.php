<?php
require "../../common.php";
session_start();
if (!isAdminLegal()) {
    header("Status: 422 Unprocessable Entity");
    return;
}

if (!isset($_POST['originID'], $_POST['name'], $_POST['studentID'], $_POST['password'], $_POST['admitted'])) {
    header("Status: 422 Unprocessable Entity");
    return;
}

$message = '';
$originID = $_POST['originID'];
$name = $_POST['name'];
$studentID = $_POST['studentID'];
$password = $_POST['password'];
$admitted = $_POST['admitted'];
$db = DB::getInstance();
if ($name !== '' && !$db->setUserName($originID, $name)) {
    $message = '修改用户名失败！';
}
if ($password !== '' && !$db->setUserPassword($originID, $password, false)) {
    $message .= '修改密码失败！';
}
if ($admitted == true) {
    if (!$db->admitUser($originID)) {
        $message .= '启用用户失败！';
    }
} else {
    if (!$db->denyUser($originID)) {
        $message .= '停用用户失败！';
    }
}
if ($studentID !== '' && $originID !== $studentID && !$db->setUserStudentID($originID, $studentID)) {
    $message .= '修改学号失败！';
}

if ($message !== '') {
    header("Status: 422 Unprocessable Entity");
    echo $message;
}
