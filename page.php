<?php
require "common.php";
session_start();
//if (isUserLegal() || isAdminLegal()) {
//    header('location: index.php');
//    return;
//}

if (!isset($_GET['id'])) {
    header('location: index.php');
    return;
}

//$studentID=$_SESSION['studentID'];

$id = $_GET['id'];
$db = &DB::getInstance();
$db->initMessageInfoByArticleId($id);
$studentID=$_SESSION['studentID'];
$name=$_SESSION['name'];
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title></title>
    <link type="text/css" rel="stylesheet" href="css/core.css">
    <link type="text/css" rel="stylesheet" href="css/home.css">
</head>
<body>

<!-- head YDC begin -->
<header class="ydc-header">
    <div class="ydc-entered">
        <div class="ydc-header-content ydc-flex">
            <div class="ydc-column">
                <a href="index.php" class="ydc-column-ydc-logo">
                    <img src="images/icon/ydc-logo.png" title="" about="" alt="">
                </a>
            </div>
            <div class="ydc-column">
                <div class="ydc-column-user">
                    <div class="ydc-user-info">
                        <div class="ydc-user-info-name">
                            <?= $name ?>
                        </div>
                        <div class="ydc-user-info-func ydc-flex">
<!--                            <span class="ydc-tag">新手期</span>-->
                            <span class="ydc-mal"><i class="ydc-icon ydc-icon-mail fl"></i><em><?= $studentID ?></em></span>
                            <a href="logout.php">退出</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- head YDC end -->

<!-- content YDC begin -->
<section>
    <div class="ydc-content-slide ydc-body">
        <div class="ydc-page-content">
            <div class="ydc-page-head">
                <h3><?= $db->getArticleTitle($id) ?></h3>
                <p><?= $db->getArticleContent($id) ?></p>
            </div>
        </div>
    </div>
    <div style="margin: 0 17.5%">
        <div class="ydc-panes">
            <div class="ydc-pane" style="display:block;">
                <ol class="ydc-pane-list">
                    <?php
                    while ($message = $db->getNextMessage()) {
                        echo <<<html
                    <li>
                        {$message->getStudentID()}:{$message->getMessage()}
                        <span>{$message->getTime()}</span>
                    </li>
html;
                    }
                    ?>
                </ol>
                <div>
                    <form>
                        <p>评论：</p>
                        <textarea style="width: 100%;height: 100px;margin: 1% auto"></textarea>
                        <button type="submit" class="ydc-reg-form-button" style="float: right;">提交</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <div class="ydc-footer">
        <div>
            <p></p>
        </div>
    </div>
</section>
<!-- content YDC end -->

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript">
    var slideIndex = 0;
    showSlides();

    function showSlides() {
        var i;
        var slides = document.getElementsByClassName("mySlides");
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        slideIndex++;
        if (slideIndex > slides.length) {
            slideIndex = 1
        }
        slides[slideIndex - 1].style.display = "block";
        setTimeout(showSlides, 3000); // 滚动时间
    }
</script>

<script type="text/javascript">
    $(function () {
        $('.ydc-tabPanel ul li').click(function () {
            $(this).addClass('hit').siblings().removeClass('hit');
            $('.ydc-panes>div:eq(' + $(this).index() + ')').show().siblings().hide();
        })
    })
</script>

</body>
</html>