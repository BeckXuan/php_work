<?php
session_start();
require "common.php";
if (!isUserLegal() && !isAdminLegal()) {
    header('location: login.php');
    return;
}
?>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
已注册，已登录
</body>
</html>