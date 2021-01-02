<?php
require "common.php";
session_start();
if (!isUserLegal() && !isAdminLegal()) {
    header('location: login.php');
    return;
}
$studentID = $_SESSION['studentID'];
$name = $_SESSION['name'];

$db =& DB::getInstance();
$db->initArticleInformation(0, 9999);
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>主页</title>
    <link type="text/css" rel="stylesheet" href="css/core.css">
    <link type="text/css" rel="stylesheet" href="css/home.css">
</head>
<body>

<!-- head YDC begin -->
<header class="ydc-header">
    <div class="ydc-entered">
        <div class="ydc-header-content ydc-flex">
            <div class="ydc-column">
                <div class="ydc-column-user">
                    <div class="ydc-user-info">
                        <div class="ydc-user-info-name">
                            <?= $name ?>
                        </div>
                        <div class="ydc-user-info-func ydc-flex">
                            <span class="ydc-tag" style="background-color: limegreen"><?= $studentID ?></span>
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
        <div class="ydc-entered-box">
            <div class="ydc-content-right">
                <div class="ydc-right-head">
                    <div class="ydc-right-head-info">
                        <dl>
                            <a href="#">
                                <dt>文章总数</dt>
                                <dd><?= $db->getNrOfArticles() ?></dd>
                            </a>
                        </dl>
                        <dl>
                            <a href="#">
                                <dt>留言总数</dt>
                                <dd><?= $db->getNrOfMessages() ?></dd>
                            </a>
                        </dl>
                    </div>
                </div>
                <!-- gongGao begin -->
                <div class="ydc-loading-box">
                    <div class="ydc-tabPanel">
                        <div>
                            <ul>
                                <li class="hit">公告栏</li>
                            </ul>
                        </div>
                        <div class="ydc-panes">
                            <div class="ydc-pane" style="display:block;">
                                <ol class="ydc-pane-list">
                                    <?php
                                    while ($article = $db->getNextArticle()) {
                                        echo <<<html
                                    <li>
                                        <a href="page.php?id={$article->getId()}" target="_blank" >{$article->getTitle()}</a >
                                        <span >{$article->getTime()}</span >
                                    </li >
html;
                                    }
                                    ?>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- gongGao End -->
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