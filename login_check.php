<?php
session_start();

if (isset($_SESSION['name']) && isset($_SESSION['studentID']) && isset($_SESSION['password'])) {
    header('location: content.html');
    return;
}

if (!isset($_POST['type'])) {
    header('location: login.html');
    return;
}

//引入DB.php
require 'DB.php';

//登录处理
if ($_POST['type'] === 'login') {
    $db = DB::getInstance();

    //获取参数
    $studentID = $_POST['studentID'];
    $password = md5($_POST['password']);
    $name = $db->getUserName($studentID);

    if ($db->studentIDExists($studentID)) {
        if ($db->getUserPassword($studentID) === $password) {
            //写入Session、cookie，并转到内容界面
            $_SESSION['name'] = $name;
            $_SESSION['studentID'] = $studentID;
            $_SESSION['password'] = $password;
            setcookie('name', $name, time() + 3600);
            setcookie('studentID', $studentID, time() + 3600);
            setcookie('password', $password, time() + 3600);
            header('location: content.html');
        }
        if(!isset($_POST['rem'])||$_POST['rem']!=='1'){
            session_destroy();
        }
    } else {
        echo "用户名不存在！";
    }
}

//注册处理
if ($_POST['type'] === 'register') {
    $db = DB::getInstance();

    //获取参数
    $name = $_POST['name'];
    $studentID = $_POST['studentID'];
    $password = $_POST['password'];

    if (!($db->nameExists($name)) && !($db->studentIDExists($studentID))) {
        if ($db->addUser($name, $studentID, $password, true)) {
            //写入Session、cookie，并转到内容界面
            $_SESSION['name'] = $name;
            $_SESSION['studentID'] = $studentID;
            $_SESSION['password'] = $password;
            setcookie('name', $name, time() + 3600);
            setcookie('studentID', $studentID, time() + 3600);
            setcookie('password', $password, time() + 3600);
            header('location: content.html');
        } else {
            echo '注册失败！';
        }
    } else {
        echo '用户名或学号已存在！';
    }
}

