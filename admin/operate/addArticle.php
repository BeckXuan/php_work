<?php
require "../../common.php";
setContentType();
session_start();
if (!isAdminLegal()) {
    http_response_code(401);
    return;
}
if (!isset($_POST['title'], $_POST['content'])) {
    header("Status: 422 Unprocessable Entity");
    return;
}

$db = DB::getInstance();
if (($articleId = $db->addArticle($_POST['title'], $_POST['content'])) !== false) {
    $time = $db->getArticleTime($articleId);
    $json = json_encode(compact('articleId', 'time'));
    echo $json;
} else {
    header("Status: 422 Unprocessable Entity");
    echo '添加失败！';
    return;
}
