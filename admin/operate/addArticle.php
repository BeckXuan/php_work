<?php
require "../../common.php";
setContentType();
session_start();
if (!isAdminLegal()) {
    http_response_code(401);
    return;
}
if (!isset($_POST['title'], $_POST['content'])) {
    http_response_code(400);
    return;
}

$db = DB::getInstance();
if (($articleId = $db->addArticle($_POST['title'], $_POST['content'])) !== false) {
    $time = $db->getArticleTime($articleId);
    $json = json_encode(compact('articleId', 'time'));
    echo $json;
} else {
    http_response_code(406);
    echo '添加失败！';
    return;
}
