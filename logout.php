<?php

session_start();
session_unset();

function unSetCookie($cookieName)
{
    if (isset($_COOKIE[$cookieName])) {
        setcookie($cookieName, '', 0, '/');
    }
}

unSetCookie('name');
unSetCookie('studentID');
unSetCookie('adminName');
unSetCookie('adminAccount');

header('location: login.php');
