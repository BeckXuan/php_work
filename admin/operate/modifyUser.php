<?php
require "../../common.php";
setContentType();
session_start();
if (!isAdminLegal()) {
    http_response_code(401);
    return;
}

if (!isset($_POST['originID'], $_POST['name'], $_POST['studentID'], $_POST['password'], $_POST['admitted'])) {
    header("Status: 422 Unprocessable Entity");
    return;
}

$originID = $_POST['originID'];
$db = DB::getInstance();
if (!$db->studentIDExists($originID)) {
    header("Status: 422 Unprocessable Entity");
    echo '该学号不存在！';
    return;
}
$error = '';
$name = $_POST['name'];
$studentID = $_POST['studentID'];
$password = $_POST['password'];
$admitted = $_POST['admitted'];
if ($name !== '' && $db->getUserName($originID) !== $name) {
    if ($db->nameExists($name)) {
        $error = '新用户名已被使用！';
    } else if (!$db->setUserName($originID, $name)) {
        $error = '修改用户名失败！';
    }
}
if ($password !== '' && $db->getUserPassword($originID) !== $password && !$db->setUserPassword($originID, $password, false)) {
    $error .= '修改密码失败！';
}
if ($admitted !== '') {
    $_admitted = $db->isUserAdmitted($originID);
    if ($admitted === '1') {
        if (!$_admitted && !$db->admitUser($originID)) {
            $error .= '启用用户失败！';
        }
    } else if ($admitted === '-1') {
        if ($_admitted && !$db->denyUser($originID)) {
            $error .= '停用用户失败！';
        }
    } else {
        $error .= '修改用户状态参数错误！';
    }
}
if ($studentID !== '' && $originID !== $studentID) {
    if ($db->studentIDExists($studentID)) {
        $error = '新学号已被使用！';
    } else if (!$db->setUserStudentID($originID, $studentID)) {
        $error .= '修改学号失败！';
    }
}
if ($error !== '') {
    header("Status: 422 Unprocessable Entity");
    echo $error;
}
