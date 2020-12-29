<?php
require "../common.php";
session_start();
//if (!isAdminLegal()) {
//    header('location: login.php');
//    return;
//}
$db = &DB::getInstance();
$db->initUserInformation(0, 10);
?>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8"/>
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
    <title>用户列表</title>
</head>
<body>
<div class="page-content clearfix">
    <div id="Member_Ratings">
        <div class="d_Confirm_Order_style">
            <div class="border clearfix">
                <span class="l_f">
                    <a href="javascript:void(0)" id="member_add" class="btn btn-warning"><i
                                class="icon-plus"></i>添加用户</a>
                    <a href="javascript:void(0)" class="btn btn-danger"><i class="icon-trash"></i>批量删除</a>
                    共：<b><?= $db->getNrOfUsers() ?></b>条
                </span>
            </div>
            <div class="table_menu_list">
                <table class="table table-striped table-bordered table-hover" id="sample-table">
                    <thead>
                    <tr>
                        <th width="25"><label><input type="checkbox" class="ace"><span class="lbl"></span></label></th>
                        <th width="100">学号</th>
                        <th width="100">用户名</th>
                        <th width="180">注册时间</th>
                        <th width="70">状态</th>
                        <th width="250">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($user = $db->getNextUser()) {
                        $studentID = $user->getStudentID();
                        $name = $user->getName();
                        $time = $user->getTime();
                        $admitted = $user->isAdmitted();
                        $class_a = $admitted ? 'btn-success' : '';
                        $class_s = $admitted ? 'label-success' : 'label-default';
                        $callback = $admitted ? 'stop' : 'start';
                        $title = $admitted ? '启用' : '停用';
                        $status = !$user->isAudited() ? '待审核' : '已' . $title;
                        echo <<< tr
                    <tr>
                        <td><label><input type="checkbox" class="ace"><span class="lbl"></span></label></td>
                        <td>{$studentID}</td>
                        <td>{$name}</td>
                        <td>{$time}</td>
                        <td class="td-status"><span class="label {$class_s} radius">{$status}</span></td>
                        <td class="td-manage">
                            <a onClick="member_{$callback}(this,'{$studentID}')" href="javascript:" title="{$title}"
                               class="btn btn-xs {$class_a}"><i class="icon-ok bigger-120"></i></a>
                            <a title="编辑" onclick="member_edit('550')" href="javascript:"
                               class="btn btn-xs btn-info"><i class="icon-edit bigger-120"></i></a>
                            <a title="删除" href="javascript:" onclick="member_del(this,'{$studentID}')"
                               class="btn btn-xs btn-warning"><i class="icon-trash  bigger-120"></i></a>
                        </td>
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
<div class="add_menber" id="add_menber_style" style="display:none">
    <ul class="page-content">
        <li><label class="label_name">用&nbsp;&nbsp;户 &nbsp;名：</label><span class="add_name"><input value="" name="name"
                                                                                                   type="text"
                                                                                                   class="text_add"/></span>
        </li>
        <li><label class="label_name">学&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号：</label><span class="add_name"><input
                        name="studentID" type="text"
                        class="text_add"/></span>
        </li>
        <li>
            <label class="label_name">状&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;态：</label><span class="add_name">
            <label><input name="form-field-radio1" type="radio" checked="checked" class="ace"><span
                        class="lbl">启用</span></label>&nbsp;&nbsp;&nbsp;
            <label><input name="form-field-radio1" type="radio" class="ace"><span class="lbl">停用</span></label></span>
        </li>
    </ul>
</div>
</body>
</html>
<script>
    jQuery(function ($) {
        let oTable1 = $('#sample-table').dataTable({
            "aaSorting": [[1, "asc"]],//默认第几个排序
            "bStateSave": true,//状态保存
        })
        $('table th input:checkbox').on('click', function () {
            let that = this;
            $(this).closest('table').find('tr > td:first-child input:checkbox')
                .each(function () {
                    this.checked = that.checked;
                    $(this).closest('tr').toggleClass('selected');
                });
        });
    })
    /*用户-添加*/
    $('#member_add').on('click', function () {
        layer.open({
            type: 1,
            title: '添加用户',
            shadeClose: true, //点击遮罩关闭层
            area: ['280px', ''],
            content: $('#add_menber_style'),
            btn: ['提交', '取消'],
            yes: function (index) {
                let flag = false
                $(".add_menber input[type$='text']").each(function (n) {
                    if ($(this).val() === "") {
                        layer.alert("所填信息不能为空！", {
                            title: '提示框',
                            icon: 0
                        });
                        flag = true
                        return false;
                    }
                });
                if (flag) {
                    return false;
                }

                layer.alert('添加成功！', {
                    title: '提示框',
                    icon: 1,
                });
                layer.close(index);
            }
        });
    });

    /*用户-停用*/
    function member_stop(obj, id) {
        layer.confirm('确认要停用吗？', function (index) {
            let _obj = $(obj)
            let tr = _obj.parents("tr")
            tr.find(".td-manage").prepend('<a style="text-decoration:none" class="btn btn-xs " onClick="member_start(this, ' + id + ')" href="javascript:;" title="启用"><i class="icon-ok bigger-120"></i></a>');
            tr.find(".td-status").html('<span class="label label-defaunt radius">已停用</span>');
            _obj.remove();
            layer.msg('已停用!', {icon: 5, time: 1000});
        });
    }

    /*用户-启用*/
    function member_start(obj, id) {
        layer.confirm('确认要启用吗？', function (index) {
            let _obj = $(obj)
            let tr = _obj.parents("tr")
            tr.find(".td-manage").prepend('<a style="text-decoration:none" class="btn btn-xs btn-success" onClick="member_stop(this, ' + id + ')" href="javascript:;" title="停用"><i class="icon-ok bigger-120"></i></a>');
            tr.find(".td-status").html('<span class="label label-success radius">已启用</span>');
            _obj.remove();
            layer.msg('已启用!', {icon: 6, time: 1000});
        });
    }

    /*用户-编辑*/
    function member_edit(id) {
        layer.open({
            type: 1,
            title: '修改用户信息',
            shadeClose: true, //点击遮罩关闭层
            area: ['280px', ''],
            content: $('#add_menber_style'),
            btn: ['提交', '取消'],
            yes: function (index) {
                let flag = false;
                $(".add_menber input[type$='text']").each(function (n) {
                    if ($(this).val() === "") {
                        layer.alert("所填信息不能为空！", {
                            title: '提示框',
                            icon: 0,
                        });
                        flag = true;
                        return false;
                    }
                });
                if (flag) {
                    return false;
                }
                layer.alert('添加成功！', {
                    title: '提示框',
                    icon: 1,
                });
                layer.close(index);
            }
        });
    }

    /*用户-删除*/
    function member_del(obj, id) {
        layer.confirm('确认要删除吗？', function () {
            let xhr = new XMLHttpRequest()

            $(obj).parents("tr").remove()
            layer.msg('已删除!', {icon: 1, time: 2000})
        });
    }
</script>