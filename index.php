<?php
session_start();
require "common.php";
if (!isUserLegal()) {
    jumpToLogin();
    return;
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
已注册，已登录
</body>
</html>