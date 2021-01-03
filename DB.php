<?php

class DB
{
    private static $_instance;
    private $_db;
    private $_result_users;
    private $_result_articles;
    private $_result_messages;

    private function __construct()
    {
        require "config/DB.php";
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

    private function getNrOfRows($tableName, $addition = '')
    {
        $result = $this->_db->query("SELECT COUNT(*) FROM `$tableName` $addition");
        if (!$result) {
            return -1;
        }
        $out = $result->fetch_array()[0];
        $result->close();
        return $out;
    }

    private function userContentExists($field, $value)
    {
        $stmt = $this->_db->prepare("SELECT 1 FROM `user` WHERE `$field`=? LIMIT 1");
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
        return $this->userContentExists('studentID', $studentID);
    }

    public function nameExists($name)
    {
        return $this->userContentExists('name', $name);
    }

    private function getTableInformation($table, $idName, $idType, $id, $field, $outHTMLFilter = true)
    {
        $stmt = $this->_db->prepare("SELECT `$field` FROM `$table` WHERE `$idName`=? limit 1");
        $stmt->bind_param($idType, $id);
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

    private function initTableInformation($table, &$result, $orderBy, $start, $counter, $descending = false, $addition = '')
    {
        if ($result) {
            $result->close();
            $result = null;
        }
        $order = $descending ? 'DESC' : 'ASC';
        $stmt = $this->_db->prepare("SELECT * FROM `$table` $addition ORDER BY `$orderBy` $order LIMIT ?,?");
        $stmt->bind_param('ii', $start, $counter);
        if (!$stmt->execute()) {
            $stmt->close();
            return 0;
        }
        $result = $stmt->get_result();
        $stmt->close();
        return $result->num_rows;
    }

    public function getNextRow(&$result) //, $container)
    {
        if (!$result) {
            return null;
        }
        if ($row = $result->fetch_assoc()) {
            //return new $container($row);
            return $row;
        }
        $result->close();
        $result = null;
        return null;
    }

    private function setTableInformation($table, $idName, $idType, $id, $field, $value)
    {
        $stmt = $this->_db->prepare("UPDATE `$table` SET `$field`=? WHERE `$idName`=?");
        $stmt->bind_param('s' . $idType, $value, $id);
        if (!$stmt->execute()) {
            $stmt->close();
            return false;
        }
        $result = $stmt->affected_rows > 0;
        $stmt->close();
        return $result;
    }

    private function delTableRow($table, $idName, $idType, $id)
    {
        $stmt = $this->_db->prepare("DELETE FROM `$table` WHERE `$idName`=?");
        $stmt->bind_param($idType, $id);
        if (!$stmt->execute()) {
            $stmt->close();
            return false;
        }
        $result = $stmt->affected_rows > 0;
        $stmt->close();
        return $result;
    }

    public function addUser($name, $studentID, $password, $needMD5 = true)
    {
        if ($needMD5) {
            $password = md5($password);
        }
        $stmt = $this->_db->prepare('INSERT INTO `user` (`name`, `studentID`, `password`, `time`) VALUES (?, ?, ?, NOW())');
        $stmt->bind_param('sss', $name, $studentID, $password);
        if (!$stmt->execute()) {
            $stmt->close();
            return false;
        }
        $insertId = $stmt->insert_id;
        $stmt->close();
        return $insertId;
    }

    public function delUser($studentID)
    {
        return $this->delTableRow('user', 'studentID', 's', $studentID);
    }

    private function setUserAccess($studentID, $admitted)
    {
        $stmt = $this->_db->prepare('UPDATE `user` SET `admitted`=? WHERE `studentID`=?');
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
        return $this->setUserAccess($studentID, -1);
    }

    public function noAuditUser($studentID)
    {
        return $this->setUserAccess($studentID, 0);
    }

    public function getNrOfUsers()
    {
        return $this->getNrOfRows('user');
    }

    public function getNrOfAdmittedUsers()
    {
        return $this->getNrOfRows('user', 'WHERE `admitted`=1');
    }

    public function getNrOfDeniedUsers()
    {
        return $this->getNrOfRows('user', 'WHERE `admitted`=-1');
    }

    public function getNrOfUnauditedUsers()
    {
        return $this->getNrOfRows('user', 'WHERE `admitted`=0');
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

    public function initUserInformation($start, $counter)
    {
        return $this->initTableInformation('user', $this->_result_users, 'studentID', $start, $counter);
    }

    public function initAdmittedUserInfo($start, $counter, $orderedByTime = false, $descending = false)
    {
        $orderedBy = $orderedByTime ? 'time' : 'studentID';
        return $this->initTableInformation('user', $this->_result_users, $orderedBy, $start, $counter, $descending, 'WHERE `admitted`=1');
    }

    public function initNoAuditedUserInfo($start, $counter, $orderedByTime = true, $descending = true)
    {
        $orderedBy = $orderedByTime ? 'time' : 'studentID';
        return $this->initTableInformation('user', $this->_result_users, $orderedBy, $start, $counter, $descending, 'WHERE `admitted`=0');
    }

    public function initDeniedUserInfo($start, $counter, $orderedByTime = true, $descending = true)
    {
        $orderedBy = $orderedByTime ? 'time' : 'studentID';
        return $this->initTableInformation('user', $this->_result_users, $orderedBy, $start, $counter, $descending, 'WHERE `admitted`=-1');
    }

    public function getNextUser()
    {
        // return $this->getNextRow($this->_result_users, 'User');
        if ($row = $this->getNextRow($this->_result_users)) {
            return new User($row);
        }
        return null;
    }

    private function getUserInformation($studentID, $field, $outHTMLFilter = true)
    {
        return $this->getTableInformation('user', 'studentID', 's', $studentID, $field, $outHTMLFilter);
    }

    public function getUserName($studentID, $outHTMLFilter = true)
    {
        return $this->getUserInformation($studentID, 'name', $outHTMLFilter);
    }

    public function getUserPassword($studentID, $outHTMLFilter = true)
    {
        return $this->getUserInformation($studentID, 'password', $outHTMLFilter);
    }

    public function getUserTime($studentID, $outHTMLFilter = true)
    {
        return $this->getUserInformation($studentID, 'time', $outHTMLFilter);
    }

    public function isUserAdmitted($studentID)
    {
        return $this->getUserInformation($studentID, 'admitted', false) === 1;
    }

    public function isUserAudited($studentID)
    {
        return $this->getUserInformation($studentID, 'admitted', false) !== 0;
    }

    private function setUserInformation($studentID, $field, $value)
    {
        return $this->setTableInformation('user', 'studentID', 's', $studentID, $field, $value);
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

    public function articleExists($articleId)
    {
        $stmt = $this->_db->prepare("SELECT 1 FROM `article` WHERE `id`=? LIMIT 1");
        $stmt->bind_param('i', $articleId);
        $stmt->execute();
        $result = $stmt->get_result();
        $existed = $result->num_rows > 0;
        $result->close();
        $stmt->close();
        return $existed;
    }

    public function addArticle($title, $content)
    {
        $stmt = $this->_db->prepare('INSERT INTO `article` (`title`, `content`, `time`) VALUES (?, ?, NOW())');
        $stmt->bind_param('ss', $title, $content);
        if (!$stmt->execute()) {
            $stmt->close();
            return false;
        }
        $insertId = $stmt->insert_id;
        $stmt->close();
        return $insertId;
    }

    public function delArticle($articleId)
    {
        return $this->delTableRow('article', 'id', 'i', $articleId);
    }

    function getNrOfArticles()
    {
        return $this->getNrOfRows('article');
    }

    public function initArticleInformation($start, $counter, $descending = false)
    {
        return $this->initTableInformation('article', $this->_result_articles, 'id', $start, $counter, $descending);
    }

    public function getNextArticle()
    {
        // return $this->getNextRow($this->_result_articles, 'Article');
        if ($row = $this->getNextRow($this->_result_articles)) {
            return new Article($row);
        }
        return null;
    }

    private function getArticleInformation($articleId, $field, $outHTMLFilter = true)
    {
        return $this->getTableInformation('article', 'id', 's', $articleId, $field, $outHTMLFilter);
    }

    public function getArticleTitle($articleId, $outHTMLFilter = true)
    {
        return $this->getArticleInformation($articleId, 'title', $outHTMLFilter);
    }

    public function getArticleContent($articleId, $outHTMLFilter = true)
    {
        return $this->getArticleInformation($articleId, 'content', $outHTMLFilter);
    }

    public function getArticleTime($articleId, $outHTMLFilter = true)
    {
        return $this->getArticleInformation($articleId, 'time', $outHTMLFilter);
    }

    private function setArticleInformation($articleId, $field, $value)
    {
        return $this->setTableInformation('article', 'id', 'i', $articleId, $field, $value);
    }

    public function setArticleTitle($articleId, $newName)
    {
        return $this->setArticleInformation($articleId, 'title', $newName);
    }

    public function setArticleContent($articleId, $newContent)
    {
        return $this->setArticleInformation($articleId, 'content', $newContent);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function messageExists($messageId)
    {
        $stmt = $this->_db->prepare("SELECT 1 FROM `message` WHERE `id`=? LIMIT 1");
        $stmt->bind_param('i', $messageId);
        $stmt->execute();
        $result = $stmt->get_result();
        $existed = $result->num_rows > 0;
        $result->close();
        $stmt->close();
        return $existed;
    }

    public function addMessage($articleId, $message, $studentID)
    {
        $stmt = $this->_db->prepare('INSERT INTO `message` (`articleId`, `message`, `studentID`, `time`) VALUES (?, ?, ?, NOW())');
        $stmt->bind_param('iss', $articleId, $message, $studentID);
        if (!$stmt->execute()) {
            $stmt->close();
            return false;
        }
        $insertId = $stmt->insert_id;
        $stmt->close();
        return $insertId;
    }

    public function delMessage($messageId)
    {
        return $this->delTableRow('message', 'id', 'i', $messageId);
    }

    public function getNrOfMessages()
    {
        return $this->getNrOfRows('message');
    }

    public function getNrOfMessagesByArticleId($articleId)
    {
        $stmt = $this->_db->prepare("SELECT COUNT(*) FROM `message` WHERE `articleId`=?");
        $stmt->bind_param('i', $articleId);
        if (!$stmt->execute()) {
            $stmt->close();
            return -1;
        }
        $result = $stmt->get_result();
        if (!$result) {
            return -1;
        }
        $out = $result->fetch_array()[0];
        $result->close();
        $stmt->close();
        return $out;
    }

    private function getMessageInformation($messageId, $field, $outHTMLFilter = true)
    {
        return $this->getTableInformation('message', 'id', 'i', $messageId, $field, $outHTMLFilter);
    }

    public function getMessageContent($messageId, $outHTMLFilter = true)
    {
        return $this->getMessageInformation($messageId, 'message', $outHTMLFilter);
    }

    public function getMessageStudentID($messageId, $outHTMLFilter = true)
    {
        return $this->getMessageInformation($messageId, 'studentID', $outHTMLFilter);
    }

    public function getMessageTime($messageId, $outHTMLFilter = true)
    {
        return $this->getMessageInformation($messageId, 'time', $outHTMLFilter);
    }

    public function initMessageInformation($start, $counter, $descending = false)
    {
        return $this->initTableInformation('message', $this->_result_messages, 'id', $start, $counter, $descending);
    }

    public function initMessageInfoByArticleId($articleId)
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
        // return $this->getNextRow($this->_result_messages, 'Message');
        if ($row = $this->getNextRow($this->_result_messages)) {
            return new Message($row);
        }
        return null;
    }

    private function setMessageInformation($messageId, $field, $value)
    {
        return $this->setTableInformation('message', 'id', 'i', $messageId, $field, $value);
    }

    public function setMessageContent($messageId, $newMessage)
    {
        return $this->setMessageInformation($messageId, 'message', $newMessage);
    }

    public function setMessageStudentID($messageId, $newStudentID)
    {
        return $this->setMessageInformation($messageId, 'studentID', $newStudentID);
    }

    public function setMessageArticleID($messageId, $newArticleID)
    {
        return $this->setMessageInformation($messageId, 'articleId', $newArticleID);
    }
}

class User
{
    private $array;

    public function __construct($array)
    {
        $this->array = $array;
    }

    private function getInformation($field, $outHTMLFilter = true)
    {
        $value = $this->array[$field];
        if ($outHTMLFilter) {
            $value = htmlspecialchars($value);
        }
        return $value;
    }

    public function getName($outHTMLFilter = true)
    {
        return $this->getInformation('name', $outHTMLFilter);
    }

    public function getStudentID($outHTMLFilter = true)
    {
        return $this->getInformation('studentID', $outHTMLFilter);
    }

    public function getPassword($outHTMLFilter = true)
    {
        return $this->getInformation('password', $outHTMLFilter);
    }

    public function getTime($outHTMLFilter = true)
    {
        return $this->getInformation('time', $outHTMLFilter);
    }

    public function isAdmitted()
    {
        return $this->getInformation('admitted', false) === 1;
    }

    public function isAudited()
    {
        return $this->getInformation('admitted', false) !== 0;
    }
}

class Article
{
    private $array;

    public function __construct($array)
    {
        $this->array = $array;
    }

    private function getInformation($field, $outHTMLFilter = true)
    {
        $value = $this->array[$field];
        if ($outHTMLFilter) {
            $value = htmlspecialchars($value);
        }
        return $value;
    }

    public function getId($outHTMLFilter = true)
    {
        return $this->getInformation('id', $outHTMLFilter);
    }

    public function getTitle($outHTMLFilter = true)
    {
        return $this->getInformation('title', $outHTMLFilter);
    }

    public function getContent($outHTMLFilter = true)
    {
        return $this->getInformation('content', $outHTMLFilter);
    }

    public function getTime($outHTMLFilter = true)
    {
        return $this->getInformation('time', $outHTMLFilter);
    }
}

class Message
{
    private $array;

    public function __construct($array)
    {
        $this->array = $array;
    }

    private function getInformation($field, $outHTMLFilter = true)
    {
        $value = $this->array[$field];
        if ($outHTMLFilter) {
            $value = htmlspecialchars($value);
        }
        return $value;
    }

    public function getId($outHTMLFilter = true)
    {
        return $this->getInformation('id', $outHTMLFilter);
    }

    public function getArticleId($outHTMLFilter = true)
    {
        return $this->getInformation('articleId', $outHTMLFilter);
    }

    public function getMessage($outHTMLFilter = true)
    {
        return $this->getInformation('message', $outHTMLFilter);
    }

    public function getStudentID($outHTMLFilter = true)
    {
        return $this->getInformation('studentID', $outHTMLFilter);
    }

    public function getTime($outHTMLFilter = true)
    {
        return $this->getInformation('time', $outHTMLFilter);
    }
}
