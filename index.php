<?php
session_start();
require "common.php";
//if (!isUserLegal() && !isAdminLegal()) {
//    header('location: login.php');
//    return;
//}
$db =& DB::getInstance();
$db->initArticleInformation(0, 5, true);
function getNextArticleParam(&$href, &$title, &$content, &$year_month, &$day)
{
    global $db;
    if ($article = $db->getNextArticle()) {
        $href = 'article_show.php?id=' . $article->getId();
        $title = $article->getTitle();
        $content = $article->getContent();
        $time = strtotime($article->getTime());
        $year_month = date('Y-m', $time);
        $day = date('d', $time);
    } else {
        $href = '#';
        $title = '/';
        $content = '/';
        $year_month = 'null';
        $day = 'null';
    }
}

?>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>主页</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>

<div class="container">
    <div class="main">
        <div class="title">
            <h4>主页</h4>
            <p>Articles</p>
        </div>
        <ul>
            <li class="one">
                <a href="articles.php">
                    <img src="images/new-cont.jpg" alt="arrow"/>
                    <div class="news-title">
                        <h5>查看更多文章</h5>
                        <p>More articles</p>
                        <i class="icon-news"></i>
                    </div>
                </a>
            </li>
            <li class="two">
<?php for ($i = 0; $i < 2; $i++) {
                    getNextArticleParam($href, $title, $content, $year_month, $day);
                    echo <<<html
                <a href="{$href}">
                    <div class="top">
                        <h5>{$title}</h5>
                        <div class="p">
                            <p>{$content}</p>
                        </div>
                        <img src="images/new-arrow.jpg" alt="arrow"/>
                    </div>
                    <div class="bottom">
                        <h3>{$day}</h3>
                        <span>{$year_month}</span>
                    </div>
                </a>

html;
                } ?>
            <li class="three">
<?php for ($i = 0; $i < 3; $i++) {
                    getNextArticleParam($href, $title, $content, $year_month, $day);
                    echo <<< html
                <a href="{$href}">
                    <div class="left">
                        <h3>{$day}</h3>
                        <span>{$year_month}</span>
                    </div>
                    <div class="right">
                        <h5>{$title}</h5>
                        <div class="p">
                            <p>{$content}</p>
                        </div>
                        <img src="images/new-arrow.jpg" alt="arrow"/>
                    </div>
                </a>

html;
                } ?>
            </li>
        </ul>
    </div>
</div>
</body>
</html>