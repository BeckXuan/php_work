<?php

function isUserLegal() {
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

function setContentType() {
    header("Content-type: text/html; charset=utf-8");
}

function jumpToLogin() {
    header('location: login.php');
}

function jumpToAdminLogin() {
    header('location: login_admin.php');
}

function jumpToIndex() {
    header('location: index.php');
}

function jumpToAdminIndex() {
    header('location: index_admin.php');
}
