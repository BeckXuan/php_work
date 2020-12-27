<?php
require('common.php');
setSessionSavePath();
session_start();
//if (!isUserLegal() && !isAdminLegal()) {
//    header('location: login.php');
//    return;
//}
if (!isset($_GET['id'])) {
    //header('location: articles.php');
    header('location: index.php');
    return;
}


$id = $_GET['id'];
$db = DB::getInstance();
$db->initMessageInfoByArticleId($id);

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title><?= $db->getArticleTitle($id) ?></title>
    <link rel="stylesheet" href="css/index.css">
    <style type="text/css">
        #title {
            text-align: center;
            padding: 2%;
        }

        #content {
            margin: 2% 7%;
            width: 86%;
            word-break: break-all;
        }

        #message {
            margin: 2% 7%;
            width: 86%;
            word-break: break-all;
        }

        #bottom {
            text-align: center;
        }

        #write_message {
            margin: 2% 7%;
        }

        textarea {
            margin-top: 20px;
            width: 100%;
            height: 250px;
        }
    </style>
</head>
<body>
<div id="container">
    <div id="title"><h1><?= $db->getArticleTitle($id) ?></h1></div>
    <div id="content"><label><?= $db->getArticleContent($id) ?></label></div>
    <div id="message">
        <?php
        while ($message = $db->getNextMessage()) {
            echo '<p>' . $db->getUserName($message->getStudentID()) . ' : ' . $message->getMessage() . '</p>';
        }
        ?>
    </div>
    <div id="write_message">
        <label>
            评论：
            <textarea id="write" name="message"></textarea>
        </label>
    </div>
    <div id="bottom">
        <a href="article_show.php?id=<?= $id - 1 ?>">上一篇</a>&nbsp;
        <a href="articles.php">返回</a>&nbsp;
        <a href="article_show.php?id=<?= $id + 1 ?>">下一篇</a>
    </div>
</div>

</body>
</html>