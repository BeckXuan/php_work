<?php

require_once "DB.php";

function isUserLegal()
{
    return isset($_SESSION['studentID'], $_COOKIE['studentID'], $_COOKIE['name'])
        && $_SESSION['studentID'] === $_COOKIE['studentID']
        && $_SESSION['name'] === $_COOKIE['name'];
}

function isAdminLegal()
{
    return isset($_SESSION['account'], $_SESSION['name'], $_COOKIE['account'], $_COOKIE['name'])
        && $_SESSION['account'] === $_COOKIE['account']
        && $_SESSION['name'] === $_COOKIE['name'];
}

function setContentType()
{
    header("Content-type: text/html; charset=utf-8");
}
