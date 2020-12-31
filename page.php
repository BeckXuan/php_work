<?php
require "common.php";


if (!isset($_GET['id'])) {
    header('location: index.php');
    return;
}
$db =& DB::getInstance();
$db->initMessageInformation(0, 9999);
$id = $_GET['id'];
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name='TTUNION_verify' content='b846c3c2b85efabc496d2a2b8399cd62'>
    <meta name="sogou_site_verification" content="gI1bINaJcL"/>
    <meta name="360-site-verification" content="37ae9186443cc6e270d8a52943cd3c5a"/>
    <meta name="baidu_union_verify" content="99203948fbfbb64534dbe0f030cbe817">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE;chrome=1">
    <meta name="format-detection" content="telephone=no">
    <meta name="keywords" content="一点车 -  让您多懂一点车,一点车，让您多懂一点车的常识，在这里，您会看到汽车相关的知识，汽车日常保养，汽车多用小知识，汽车简单维修以及清洗保养等等。">
    <meta name="description" content="一点车 -  让您多懂一点车,一点车，让您多懂一点车的常识，在这里，您会看到汽车相关的知识，汽车日常保养，汽车多用小知识，汽车简单维修以及清洗保养等等。。">
    <meta name="author" content="AUI, a-ui.com">
    <meta name="baidu-site-verification" content="ZVPGgtpUfW"/>
    <title>公告栏-- 一点车 - 让您多懂一点车</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link href="iTunesArtwork@2x.png" sizes="114x114" rel="apple-touch-icon-precomposed">
    <link type="text/css" rel="stylesheet" href="css/core.css">
    <link type="text/css" rel="stylesheet" href="css/icon.css">
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
                    <div class="ydc-user-photo">
                        <a href="javascript:;">
                            <img src="images/icon/photo.png" title="" about="" alt="">
                        </a>
                    </div>
                    <div class="ydc-user-info">
                        <div class="ydc-user-info-name">
                            <a href="javascript:;">一点车</a>
                        </div>
                        <div class="ydc-user-info-func ydc-flex">
                            <span class="ydc-tag">新手期</span>
                            <span class="ydc-mal"><i class="ydc-icon ydc-icon-mail fl"></i><em>12</em></span>
                            <a href="javascript:;">退出</a>
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
                        <textarea style="width: 100%;height: 100px;"></textarea>
                        <button type="submit">提交</button>
                    </form>
                </div>
            </div>

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