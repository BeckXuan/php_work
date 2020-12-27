<?php
session_start();
require "common.php";
//if (!isUserLegal() && !isAdminLegal()) {
//    header('location: login.php');
//    return;
//}

$db = DB::getInstance();
$db->initArticleInformation(0, 10, true);
?>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>最新动态</title>
    <link rel="stylesheet" href="css/articles.css">
</head>
<body>
<div class="newsCenterPanel">
    <div class="title">
        <a href="#" target="_self">全部文章</a>
    </div>
    <div class="newsCenterPanel_inner">
        <?php
        while ($article = $db->getNextArticle()) {
            $year_month = substr($article->getTime(), 0, 7);
            $day = substr($article->getTime(), 9, 2);
            echo <<<html
        <div class="newContentBox odd">
            <a href="#">
                <div class="time">
                    <p class="day">{$day}</p>
                    <p class="ym">{$year_month}</p>
                </div>
                <div class="newTitle">{$article->getTitle()}</div>
                <div class="newTitleIcon"></div>
                <div class="border"></div>
                <div class="newContent">
                    {$article->getContent()}
                </div>
            </a>
        </div>
html;
            if ($article = $db->getNextArticle()) {
                $year_month = substr($article->getTime(), 0, 7);
                $day = substr($article->getTime(), 9, 2);
                echo <<<html
        <div class="newContentBox even">
            <a href="#">
                <div class="time">
                    <p class="day">{$day}</p>
                    <p class="ym">{$year_month}</p>
                </div>
                <div class="newTitle">{$article->getTitle()}</div>
                <div class="newTitleIcon"></div>
                <div class="border"></div>
                <div class="newContent">
                    {$article->getContent()}
                </div>
            </a>
        </div>
html;
            }
        }
        ?>
    </div>
</div>
</body>
</html>