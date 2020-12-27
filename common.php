<?php

require_once "DB.php";

function setSessionSavePath()
{
    $dir = 'runtime/session';
    if (!is_dir($dir)) {
        mkdir($dir, 777, true);
    }
    session_save_path(dirname(__FILE__) . '/' . $dir);
}

function isUserLegal()
{
    return isset($_SESSION['studentID'], $_COOKIE['studentID'], $_COOKIE['name'])
        && $_SESSION['studentID'] === $_COOKIE['studentID']
        && $_SESSION['name'] === $_COOKIE['name'];
}

function isAdminLegal()
{
    return isset($_SESSION['adminAccount'], $_COOKIE['adminAccount'], $_COOKIE['adminName'])
        && $_SESSION['adminAccount'] === $_COOKIE['adminAccount']
        && $_SESSION['adminName'] === $_COOKIE['adminName'];
}

function setContentType()
{
    header("Content-type: text/html; charset=utf-8");
}
