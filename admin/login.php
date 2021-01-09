<?php
require "../common.php";
session_start();
if (isAdminLegal()) {
    header('location: index.php');
    return;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>管理员登录</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon"/>
    <link rel="icon" sizes="any" href="../images/favicon.ico">
    <script src="../js/md5.js"></script>
    <script src="js/jquery-1.9.1.min.js"></script>
    <script src="assets/layer/layer.js" type="text/javascript"></script>
</head>
<body>
<div class="content">
    <form id="login">
        <div class="form sign-in">
            <h2>管理员登录</h2>
            <label>
                <span>管理员账号</span>
                <input type="text" name="account" required/>
            </label>
            <label>
                <span>密码</span>
                <input type="password" name="password" required/>
            </label>
            <label>
                <span>记住我</span>
                <input type="checkbox" name="rem">
            </label>
            <p class="forgot-pass"><a href="javascript:void(0)" id="forget">忘记密码？</a></p>
            <button type="submit" class="submit" id="btn_log">登 录</button>
            <button type="button" class="fb-btn" id="btn_usr">使用 <span>学生</span> 帐号登录</button>
        </div>
    </form>
    <div class="sub-cont">
        <div class="img">
            <div class="img__text m--up">
                <h2>欢迎使用</h2>
                <br/><br/><br/>
                <h2>后台管理系统</h2>
            </div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
    document.getElementById('forget').addEventListener('click', function () {
        layer.msg('请联系程序员处理！', {
            icon: 5,
            time: 3000
        })
    })
</script>
<script src="js/login.js"></script>
</html>
