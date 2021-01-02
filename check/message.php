<?php
require "../common.php";
session_start();
if (!isUserLegal() && !isAdminLegal()) {
    header('location: ../login.php');
    return;
}
if (!isset($_POST['message'], $_POST['articleId'])) {
    header('location: ../index.php');
    return;
}

$db = &DB::getInstance();
$message = $_POST['message'];
$articleId = $_POST['articleId'];
$studentID = $_POST['studentID'];
if ($message === "") {
    header('location: page.php?id=' . $articleId);
    return;
}
if ($db->addMessage($articleId, $message, $_SESSION['studentID']) === false) {
    header("Status: 422 Unprocessable Entity");
    echo '留言失败！';
} else {
    header("location: ../page.php?id={$articleId}");
    return;
}
