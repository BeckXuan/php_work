<?php
session_start();
require "common.php";
//if (!isUserLegal() && !isAdminLegal()) {
//    header('location: login.php');
//    return;
//}

$db = DB::getInstance();
$db->initArticleInformation(0, -1, true);
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
        $class = '';
        while ($article = $db->getNextArticle()) {
            $href = 'article_show.php?id=' . $article->getId();
            $title = $article->getTitle();
            $content = $article->getContent();
            $time = strtotime($article->getTime());
            $year_month = date('Y-m', $time);
            $day = date('d', $time);
            $class = $class === 'odd' ? 'even' : 'odd';
            echo <<<html
        <div class="newContentBox {$class}">
            <a href="#">
                <div class="time">
                    <p class="day">{$day}</p>
                    <p class="ym">{$year_month}</p>
                </div>
                <div class="newTitle">{$title}</div>
                <div class="newTitleIcon"></div>
                <div class="border"></div>
                <div class="newContent">
                    {$content}
                </div>
            </a>
        </div>
html;
        } ?>
    </div>
</div>
</body>
</html>