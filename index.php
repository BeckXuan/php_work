<?php
session_start();
require "common.php";
//if (!isUserLegal() && !isAdminLegal()) {
//    header('location: login.php');
//    return;
//}
$db =& DB::getInstance();
$db->initArticleInformation(0, 5, true);

?>
<!DOCTYPE html>
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
                    <img src="images/new-cont.jpg"/>
                    <div class="news-title">
                        <h5>查看更多文章</h5>
                        <p>More articles</p>
                        <i class="icon-news"></i>
                    </div>
                </a>
            </li>
            <li class="two">
                <?php $article = $db->getNextArticle(); ?>
                <a href="article_show.php?id=<?= $article->getId() ?>">
                    <div class="top">

                        <h5>
                            <?= $article->getTitle() ?>
                        </h5>
                        <div class="p">
                            <p>
                                <?= $article->getContent() ?>
                            </p>
                        </div>
                        <img src="images/new-jiantou.jpg">
                    </div>
                    <div class="bottom">
                        <h3><?= substr($article->getTime(), 9, 2) ?></h3>
                        <span><?= substr($article->getTime(), 0, 7) ?></span>

                    </div>
                </a>
                <?php $article = $db->getNextArticle(); ?>
                <a href="article_show.php?id=<?= $article->getId() ?>">
                    <div class="top">

                        <h5>
                            <?= $article->getTitle() ?>
                        </h5>
                        <div class="p">
                            <p>
                                <?= $article->getContent() ?>
                            </p>
                        </div>
                        <img src="images/new-jiantou.jpg">
                    </div>
                    <div class="bottom">
                        <h3><?= substr($article->getTime(), 9, 2) ?></h3>
                        <span><?= substr($article->getTime(), 0, 7) ?></span>

                    </div>
                </a>
            </li>
            <li class="three">
                <?php
                for ($id = 3;
                     $id < 6;
                     $id++) {
                    $article = $db->getNextArticle();
                    $time = $article->getTime();
                    $year_month=substr($article->getTime(), 0, 7);
                    $day=substr($article->getTime(), 9, 2);
                    echo <<<html
                    <a href="article_show.php?id={$article->getId()}">
                        <div class="left">
                            <h3>{$day}</h3>
                            <span>{$year_month}</span>
                        </div>
                        <div class="right">
                            <h5>

                    {$article->getTitle()}
           
                            </h5>
                            <div class="p">
                                <p>

                    {$article->getContent()}
            
                                </p>
                            </div>
                            <img src="images/new-jiantou.jpg"/>
                        </div>
                    </a>
                   
html;
                }
                ?>
            </li>
        </ul>
    </div>
</div>
</body>
</html>