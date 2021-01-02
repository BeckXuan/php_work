<?php
require "common.php";
session_start();
if (!isUserLegal() && !isAdminLegal()) {
    header('location: login.php');
    return;
}

if (!isset($_GET['id'])) {
    header('location: index.php');
    return;
}

//$studentID=$_SESSION['studentID'];

$id = $_GET['id'];
$db = &DB::getInstance();
$db->initMessageInfoByArticleId($id);
$studentID = $_SESSION['studentID'];
$name = $_SESSION['name'];
?>

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
                <div class="ydc-column-user">
                    <div class="ydc-user-info">
                        <div class="ydc-user-info-name">
                            <?= $name ?>
                        </div>
                        <div class="ydc-user-info-func ydc-flex">
                            <!--                            <span class="ydc-tag">新手期</span>-->
                            <span class="ydc-mal"><i
                                        class="ydc-icon ydc-icon-mail fl"></i><em><?= $studentID ?></em></span>
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
            <div class="ydc-page-head" style="word-break: break-all;">
                <h3><?= $db->getArticleTitle($id) ?></h3>
                <?php
                $content = $db->getArticleContent($id);
                $content = str_replace(' ', '&nbsp;', $content);
                $content = str_replace("\r\n", '</p><p>', $content);
                ?>
                <p><?= $content ?></p>
            </div>
        </div>
    </div>
    <div style="margin: 0 17.5%">
        <div class="ydc-panes">
            <div class="ydc-pane" style="display:block;">
                <p style="font-weight: bold;font-size: large;">共有<?= $db->getNrOfMessages() ?>条评论：</p>
                <div style="border: 5px #ebebeb solid;">
                    <ol class="ydc-pane-list">
                        <?php
                        while ($message = $db->getNextMessage()) {
                            echo <<<html
                    <li style="height: auto;">
                        <span style="font-weight: bold; float: left; font-size: large; 
                        color: crimson"> {$db->getUserName($message->getStudentID())}:&nbsp</span>{$message->getMessage()}
                        <p style="color: #00CC66;margin-bottom: 2px;padding-top: 1px">{$message->getTime()}</p>
                    </li>
html;
                        }
                        ?>
                    </ol>
                </div>
                <div>
                    <p></p>
                    <form action="check/message.php" method="post">
                        <p style="font-weight: bold;font-size: large;">快来发表你的想法吧！</p>
                        <textarea name="message" style="width: 100%;height: 100px;margin: 1% auto"
                                  required="required"></textarea>
                        <input type="hidden" name="studentID" value="<?= $studentID ?>">
                        <input type="hidden" name="articleId" value="<?= $id ?>">
                        <button type="button" class="ydc-reg-form-button" style="float: left;background-color: grey" onclick="returnIndex()">返回主页</button>
                        <button type="submit" class="ydc-reg-form-button" style="float: right;">发布评论</button>
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

<script type="text/javascript">
    function returnIndex() {
        window.location.href = "index.php";
    }
</script>
</body>
</html>