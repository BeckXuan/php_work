<?php
session_start();
if (isset($_SESSION['studentID'], $_COOKIE['studentID']) && $_SESSION['studentID'] === $_COOKIE['studentID']) {
    header('location: content.php');
    return;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>登录注册页面</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/>
    <link rel="icon" sizes="any" href="images/favicon.ico">
</head>
<body>
<div class="content">
    <form id="login">
        <div class="form sign-in">
            <h2>欢迎回来！同学们</h2>
            <label>
                <span>学号</span>
                <input type="text" name="studentID" required/>
            </label>
            <label>
                <span>密码</span>
                <input type="password" name="password" required/>
            </label>
            <label>
                <span>记住我</span>
                <input type="checkbox" name="rem">
            </label>
            <p class="forgot-pass"><a href="javascript:">忘记密码？</a></p>
            <button type="submit" class="submit" id="btn_log">登 录</button>
            <button type="button" class="fb-btn">使用 <span>管理员</span> 帐号登录</button>
        </div>
    </form>
    <div class="sub-cont">
        <div class="img">
            <div class="img__text m--up">
                <h2>还未注册？</h2>
                <br>
                <p>立即注册</p>
                <br>
                <p>与老师同学们共聚一堂</p>
            </div>
            <div class="img__text m--in">
                <h2>已有帐号？</h2>
                <br>
                <p>快来登录吧，好久不见了！</p>
            </div>
            <div class="img__btn">
                <span class="m--up">注 册</span>
                <span class="m--in">登 录</span>
            </div>
        </div>
        <form id="register">
            <div class="form sign-up">
                <h2>立即注册</h2>
                <label>
                    <span id="sp_name">用户名</span>
                    <input type="text" name="name" required/>
                </label>
                <label>
                    <span id="sp_studentID">学号</span>
                    <input type="text" name="studentID" required/>
                </label>
                <label>
                    <span id="sp_password">密码</span>
                    <a id="passwordEye"></a>
                    <input type="password" name="password" required/>
                </label>
                <button type="submit" class="submit" id="btn_reg">注 册</button>
                <button type="reset" class="fb-btn" id="btn_rst"><span>重 置</span></button>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    document.querySelector('.img__btn').addEventListener('click', function () {
        document.querySelector('.content').classList.toggle('s--signup')
    })
</script>
<script src="js/login.js"></script>
<script src="js/register.js"></script>
<script src="js/md5.js"></script>
</body>
</html>
