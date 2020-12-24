<?php

class DB
{
    private $_db;
    private $_result_messages;
    private static $_instance;

    private function __construct()
    {
        require "DBConfig.php";
        /* @noinspection PhpUndefinedVariableInspection */
        $this->_db = new mysqli($host, $user, $password, $dbname, $port);
        $error = $this->_db->connect_error;
        if ($error) {
            die('数据库连接失败！错误信息: ' . $error);
        }
        $this->_db->query('set names "utf8"');
    }

    private function __clone()
    {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    public static function &getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function &getDBConn()
    {
        return $this->_db;
    }

    public function getSqlError()
    {
        return $this->_db->error;
    }

    private function getNrOfRows($tableName) {
        $result = $this->_db->query("SELECT COUNT(*) FROM `$tableName`");
        if (!$result) {
            return 0;
        }
        $out = $result->fetch_array()[0];
        $result->close();
        return $out;
    }

    private function contentExists($field, $value)
    {
        $stmt = $this->_db->prepare("SELECT `name` FROM `user` WHERE `$field`=? LIMIT 1");
        $stmt->bind_param('s', $value);
        $stmt->execute();
        $result = $stmt->get_result();
        $existed = $result->num_rows > 0;
        $result->close();
        $stmt->close();
        return $existed;
    }

    public function studentIDExists($studentID)
    {
        return $this->contentExists('studentID', $studentID);
    }

    public function nameExists($name)
    {
        return $this->contentExists('name', $name);
    }

    public function addUser($name, $studentID, $password, $needMD5 = true)
    {
        if ($needMD5) {
            $password = md5($password);
        }
        $stmt = $this->_db->prepare('INSERT INTO `user` (`name`, `studentID`, `password`, `time`) VALUES (?, ?, ?, NOW())');
        $stmt->bind_param('sss', $name, $studentID, $password);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delUser($studentID)
    {
        $stmt = $this->_db->prepare('DELETE FROM `user` WHERE `studentID`=?');
        $stmt->bind_param('s', $studentID);
        if (!$stmt->execute()) {
            $stmt->close();
            return false;
        }
        $result = $stmt->affected_rows > 0;
        $stmt->close();
        return $result;
    }

    private function setUserAccess($studentID, $admitted)
    {
        $stmt = $this->_db->prepare('UPDATE `user` SET `admitted`=? WHERE `studentID`=?');
        if ($admitted) {
            $admitted = 1;
        } else {
            $admitted = 0;
        }
        $stmt->bind_param('is', $admitted, $studentID);
        if (!$stmt->execute()) {
            $stmt->close();
            return false;
        }
        $result = $stmt->affected_rows > 0;
        $stmt->close();
        return $result;
    }

    public function admitUser($studentID)
    {
        return $this->setUserAccess($studentID, 1);
    }

    public function denyUser($studentID)
    {
        return $this->setUserAccess($studentID, 0);
    }

    public function getNrOfUsers() {
        return $this->getNrOfRows('user');
    }

    private function getUserStudentId($field, $value)
    {
        $stmt = $this->_db->prepare("SELECT `studentID` FROM `user` WHERE `$field`=? limit 1");
        $stmt->bind_param('s', $value);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result->num_rows) {
            $result->close();
            $stmt->close();
            return null;
        }
        $out = $result->fetch_array()[0];
        $result->close();
        $stmt->close();
        return $out;
    }

    public function getUserStudentIdByName($name)
    {
        return $this->getUserStudentId('name', $name);
    }

    private function getUserInformation($studentID, $field, $outHTMLFilter = true)
    {
        $stmt = $this->_db->prepare("SELECT `$field` FROM `user` WHERE `studentID`=? limit 1");
        $stmt->bind_param('s', $studentID);
        if (!$stmt->execute()) {
            $stmt->close();
            return null;
        }
        $result = $stmt->get_result();
        if (!$result->num_rows) {
            $result->close();
            $stmt->close();
            return null;
        }
        $out = $result->fetch_array()[0];
        $result->close();
        $stmt->close();
        if ($outHTMLFilter) {
            $out = htmlspecialchars($out);
        }
        return $out;
    }

    public function getUserName($studentID, $outHTMLFilter = true)
    {
        return $this->getUserInformation($studentID, 'name', $outHTMLFilter);
    }

    public function getUserPassword($studentID, $outHTMLFilter = true)
    {
        return $this->getUserInformation($studentID, 'password', $outHTMLFilter);
    }

    public function isUserAccessible($studentID)
    {
        return $this->getUserInformation($studentID, 'admitted', false) == true;
    }

    private function setUserInformation($studentID, $field, $value)
    {
        $stmt = $this->_db->prepare("UPDATE `user` SET `$field`=? WHERE `studentID`=?");
        $stmt->bind_param('ss', $value, $studentID);
        if (!$stmt->execute()) {
            $stmt->close();
            return false;
        }
        $result = $stmt->affected_rows > 0;
        $stmt->close();
        return $result;
    }

    public function setUserStudentID($studentID, $newStudentID)
    {
        return $this->setUserInformation($studentID, 'studentID', $newStudentID);
    }

    public function setUserName($studentID, $newName)
    {
        return $this->setUserInformation($studentID, 'name', $newName);
    }

    public function setUserPassword($studentID, $newPassword, $needMD5 = true)
    {
        if ($needMD5) {
            $newPassword = md5($newPassword);
        }
        return $this->setUserInformation($studentID, 'password', $newPassword);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function addArticle($name, $content)
    {
        $stmt = $this->_db->prepare('INSERT INTO `article` (`name`, `content`, `time`) VALUES (?, ?, NOW())');
        $stmt->bind_param('ss', $name, $content);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delArticle($articleId)
    {
        $stmt = $this->_db->prepare('DELETE FROM `article` WHERE `id`=?');
        $stmt->bind_param('i', $articleId);
        if (!$stmt->execute()) {
            $stmt->close();
            return false;
        }
        $result = $stmt->affected_rows > 0;
        $stmt->close();
        return $result;
    }

    function getNrOfArticles() {
        return $this->getNrOfRows('article');
    }

    private function getArticleInformation($articleId, $field, $outHTMLFilter = true)
    {
        $stmt = $this->_db->prepare("SELECT `$field` FROM `article` WHERE `id`=? limit 1");
        $stmt->bind_param('i', $articleId);
        if (!$stmt->execute()) {
            $stmt->close();
            return null;
        }
        $result = $stmt->get_result();
        if (!$result->num_rows) {
            $result->close();
            $stmt->close();
            return null;
        }
        $out = $result->fetch_array()[0];
        $result->close();
        $stmt->close();
        if ($outHTMLFilter) {
            $out = htmlspecialchars($out);
        }
        return $out;
    }

    public function getArticleName($articleId, $outHTMLFilter = true)
    {
        return $this->getArticleInformation($articleId, 'name', $outHTMLFilter);
    }

    public function getArticleContent($articleId, $outHTMLFilter = true)
    {
        return $this->getArticleInformation($articleId, 'content', $outHTMLFilter);
    }

    public function getArticleStudentID($articleId, $outHTMLFilter = true)
    {
        return $this->getArticleInformation($articleId, 'studentID', $outHTMLFilter);
    }

    private function setArticleInformation($articleId, $field, $value)
    {
        $stmt = $this->_db->prepare("UPDATE `article` SET `$field`=? WHERE `id`=?");
        $stmt->bind_param('si', $value, $articleId);
        if (!$stmt->execute()) {
            $stmt->close();
            return false;
        }
        $result = $stmt->affected_rows > 0;
        $stmt->close();
        return $result;
    }

    public function setArticle($articleId, $newName, $newContent)
    {
        $this->setArticleName($articleId, $newName);
        $this->setArticleContent($articleId, $newContent);
    }

    public function setArticleName($articleId, $newName)
    {
        return $this->setArticleInformation($articleId, 'name', $newName);
    }

    public function setArticleContent($articleId, $newContent)
    {
        return $this->setArticleInformation($articleId, 'content', $newContent);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function addMessage($articleId, $message, $studentID)
    {
        $stmt = $this->_db->prepare('INSERT INTO `message` (`articleId`, `message`, `studentID`, `time`) VALUES (?, ?, ?, NOW())');
        $stmt->bind_param('iss', $articleId, $message, $studentID);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delMessage($messageId)
    {
        $stmt = $this->_db->prepare('DELETE FROM `message` WHERE `id`=?');
        $stmt->bind_param('i', $articleId, $messageId);
        if (!$stmt->execute()) {
            $stmt->close();
            return false;
        }
        $result = $stmt->affected_rows > 0;
        $stmt->close();
        return $result;
    }

    public function getNrOfMessages() {
        return $this->getNrOfRows('message');
    }

    public function initMessagesInfoByArticleId($articleId)
    {
        if ($this->_result_messages) {
            $this->_result_messages->close();
            $this->_result_messages = null;
        }
        $stmt = $this->_db->prepare("SELECT * FROM `message` WHERE `articleId`=? ORDER BY `id` ASC");
        $stmt->bind_param('i', $articleId);
        if (!$stmt->execute()) {
            $stmt->close();
            return false;
        }
        $this->_result_messages = $stmt->get_result();
        $stmt->close();
        return $this->_result_messages->num_rows;
    }

    public function getNextMessage()
    {
        if (!$this->_result_messages) {
            return null;
        }
        if ($row = $this->_result_messages->fetch_assoc()) {
            return new Message($row);
        }
        $this->_result_messages->close();
        $this->_result_messages = null;
        return null;
    }

    public function setMessage($messageId, $newMessage)
    {
        $stmt = $this->_db->prepare('UPDATE `article` SET `message`=? WHERE `id`=?');
        $stmt->bind_param('si', $newMessage, $messageId);
        if (!$stmt->execute()) {
            $stmt->close();
            return false;
        }
        $result = $stmt->affected_rows > 0;
        $stmt->close();
        return $result;
    }
}

class Message
{
    private $array_message;

    public function __construct($array_message)
    {
        $this->array_message = $array_message;
    }

    private function getInformation($field, $outHTMLFilter = true) {
        $value = $this->array_message[$field];
        if ($outHTMLFilter) {
            $value = htmlspecialchars($value);
        }
        return $value;
    }

    public function getMessage($outHTMLFilter = true) {
        return $this->getInformation('message', $outHTMLFilter);
    }

    public function getStudentID($outHTMLFilter = true) {
        return $this->getInformation('studentID', $outHTMLFilter);
    }

    public function getTime($outHTMLFilter = true) {
        return $this->getInformation('time', $outHTMLFilter);
    }
}