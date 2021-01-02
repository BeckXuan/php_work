<?php
require "../common.php";
session_start();
//if (!isAdminLegal()) {
//    header('location: login.php');
//    return;
//}
$db = &DB::getInstance();
$type = isset($_GET['type']) ? $_GET['type'] : null;
if ($type === '-1') {
    $db->initDeniedUserInfo(0, 100);
} else if ($type === '0') {
    $db->initNoAuditedUserInfo(0, 100);
} else if ($type === '1') {
    $db->initAdmittedUserInfo(0, 100);
} else {
    $db->initUserInformation(0, 100);
}
?>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8"/>
    <title>用户列表</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/style.css"/>
    <link rel="stylesheet" href="assets/css/ace.min.css"/>
    <link rel="stylesheet" href="assets/css/font-awesome.min.css"/>
    <script src="js/jquery-1.9.1.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/typeahead-bs2.min.js"></script>
    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/jquery.dataTables.bootstrap.js"></script>
    <script src="assets/layer/layer.js"></script>
    <script src="../js/md5.js"></script>
    <script src="js/common.js" type="text/javascript"></script>
    <script src="js/user.js" type="text/javascript"></script>
</head>
<body>
<div class="page-content clearfix">
    <div id="Member_Ratings">
        <div class="margin clearfix">
            <div class="border clearfix">
                <span class="l_f">
                    <a href="javascript:void(0)" id="member_add" class="btn btn-warning"><i
                                class="icon-plus"></i>添加用户</a>
                    <!--<a href="javascript:void(0)" class="btn btn-danger"><i class="icon-trash"></i>批量删除</a>-->
                </span>
            </div>
            <div class="table_menu_list">
                <table class="table table-striped table-bordered table-hover" id="sample-table">
                    <thead>
                    <tr>
                        <th><label><input type="checkbox" class="ace"><span class="lbl"></span></label>
                        </th>
                        <th>学号</th>
                        <th>用户名</th>
                        <th>注册时间</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($user = $db->getNextUser()) {
                        $studentID = $user->getStudentID();
                        $name = $user->getName();
                        $time = $user->getTime();
//                        $admitted = $user->isAdmitted();
//                        $class_a = $admitted ? 'btn-success' : '';
//                        $class_s = $admitted ? 'label-success' : 'label-default';
//                        $callback = $admitted ? 'stop' : 'start';
//                        $title = $admitted ? '启用' : '停用';
//                        $status = !$user->isAudited() ? '待审核' : '已' . $title;
                        $admitted = $user->isAdmitted() ? 'true' : 'false';
                        $audited = $user->isAudited() ? 'true' : 'false';
                        echo <<< tr
                    <tr>
                        <td></td>
                        <td>{$studentID}</td>
                        <td>{$name}</td>
                        <td>{$time}</td>
                        <td>{"admitted": {$admitted}, "audited": {$audited}}</td>
                        <td>{"admitted": {$admitted}}</td>
                    </tr>

tr;
                    } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!--添加用户图层-->
<div class="add_member" id="member_style" style="display:none">
    <ul class="page-content">
        <li><label class="label_name">用&nbsp;&nbsp;户 &nbsp;名：<span class="add_name"><input value="" name="name"
                                                                                           type="text"
                                                                                           class="text_add"/></span></label>
        </li>
        <li><label class="label_name">学&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号：<span class="add_name"><input
                            name="studentID" type="text"
                            class="text_add"/></span></label>
        </li>
        <li><label class="label_name">密&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;码：<span class="add_name"><input
                            name="password" type="password"
                            class="text_add"/></span></label>
        </li>
        <li>
            <label class="label_name">状&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;态：</label><span class="add_name">
            <label><input name="form-field-radio" type="radio" checked="checked" class="ace" value="1"><span
                        class="lbl">启用</span></label>
            <label><input name="form-field-radio" type="radio" class="ace" value="-1"><span
                        class="lbl">停用</span></label></span>
        </li>
    </ul>
</div>
</body>
</html>