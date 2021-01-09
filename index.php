<?php
require "common.php";
session_start();
if (!isUserLegal() && !isAdminLegal()) {
    header('location: login.php');
    return;
}
$studentID = $_SESSION['studentID'];
$name = $_SESSION['name'];

$db = &DB::getInstance();
$db->initArticleInformation(0, 9999);
?>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>主页</title>
    <link type="text/css" rel="stylesheet" href="css/core.css">
    <link type="text/css" rel="stylesheet" href="css/home.css">
    <script src="js/jquery-3.5.1.min.js" type="text/javascript"></script>
    <script src="js/layer.js" type="text/javascript"></script>
</head>
<body>
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
                            <a href="javascript:void(0)" onclick="_logout()">退出</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
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
            </div>
        </div>
    </div>

    <div class="ydc-footer">
        <div>
            <p></p>
        </div>
    </div>
</section>
<script type="text/javascript">
    function _logout() {
        layer.confirm('确实要退出吗?', () => {
            window.location.href = 'logout.php'
        })
    }
</script>
</body>
</html>