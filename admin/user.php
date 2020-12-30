<?php
require "../common.php";
session_start();
//if (!isAdminLegal()) {
//    header('location: login.php');
//    return;
//}
$db = &DB::getInstance();
$db->initUserInformation(0, 100);
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
                        <th width="25px"><label><input type="checkbox" class="ace"><span class="lbl"></span></label>
                        </th>
                        <th width="120px">学号</th>
                        <th width="120px">用户名</th>
                        <th width="180px">注册时间</th>
                        <th width="70px">状态</th>
                        <th width="250px">操作</th>
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
                            <a onClick="member_{$callback}(this)" href="javascript:" title="{$title}"
                               class="btn btn-xs {$class_a}"><i class="icon-ok bigger-120"></i></a>
                            <a title="编辑" onclick="member_edit(this)" href="javascript:"
                               class="btn btn-xs btn-info"><i class="icon-edit bigger-120"></i></a>
                            <a title="删除" href="javascript:" onclick="member_del(this)"
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
<script>
    jQuery(function ($) {
        $('#sample-table').dataTable({
            "aaSorting": [[1, "asc"]],//默认第几个排序
            "bStateSave": true,//状态保存
            "aoColumnDefs": [{"orderable": false, "aTargets": [0, 4, 5]}]
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
        let input_name = $("#member_style input[name$='name']")
        let input_studentID = $("#member_style input[name$='studentID']")
        let input_password = $("#member_style input[name$='password']")
        input_name.val('')
        input_studentID.val('')
        input_password.val('')
        $("#member_style input[type='radio']:first").attr('checked', 'checked');
        layer.open({
            type: 1,
            title: '添加用户',
            shadeClose: true, //点击遮罩关闭层
            area: ['280px', ''],
            content: $('#member_style'),
            btn: ['提交', '取消'],
            yes: function (index) {
                let name = input_name.val()
                let studentID = input_studentID.val()
                let password = hex_md5(input_password.val())
                let admitted = $("#member_style input[type='radio']:checked").val();
                if (name === '' || studentID === '' || password === '') {
                    layer.alert("所填信息不能为空！", {
                        title: '提示框',
                        icon: 0,
                    });
                    return false;
                }
                _request('../check/register.php', 'name=' + name + '&studentID=' + studentID + '&password=' + password, () => {
                    layer.close(index);
                    input_name.val('')
                    input_studentID.val('')
                    input_password.val('')
                    if (admitted) {
                        _request('operate/admitUser.php', 'value=' + studentID, () => {
                            layer.alert('添加并启用成功！(刷新后显示)', {title: '提示框', icon: 1})
                        }, (xhr) => {
                            layer.alert('添加成功但启用失败！(刷新后显示)' + xhr.responseText, {title: '提示框', icon: 2})
                        })
                    } else {
                        layer.alert('添加成功！(刷新后显示)', {title: '提示框', icon: 1})
                    }
                }, (xhr) => {
                    layer.alert('添加失败！' + xhr.responseText, {title: '提示框', icon: 2})
                })
            }
        });
    });

    /*用户-停用*/
    function member_stop(obj) {
        layer.confirm('确认要停用吗？', function () {
            let _obj = $(obj)
            let tr = _obj.parents("tr")
            let id = tr.children('td').eq(1).text()
            _request('operate/denyUser.php', 'value=' + id, () => {
                layer.msg('已停用!', {icon: 1, time: 2000});
                tr.find(".td-manage").prepend('<a style="text-decoration:none" class="btn btn-xs " onClick="member_start(this)" href="javascript:;" title="启用"><i class="icon-ok bigger-120"></i></a>');
                tr.find(".td-status").html('<span class="label label-default radius">已停用</span>');
                _obj.remove();
            }, (xhr) => {
                layer.msg('停用失败！' + xhr.responseText, {icon: 2, time: 3000})
            })
        });
    }

    /*用户-启用*/
    function member_start(obj) {
        layer.confirm('确认要启用吗？', function () {
            let _obj = $(obj)
            let tr = _obj.parents("tr")
            let id = tr.children('td').eq(1).text()
            _request('operate/admitUser.php', 'value=' + id, () => {
                layer.msg('已启用!', {icon: 1, time: 2000})
                tr.find(".td-manage").prepend('<a style="text-decoration:none" class="btn btn-xs btn-success" onClick="member_stop(this)" href="javascript:;" title="停用"><i class="icon-ok bigger-120"></i></a>');
                tr.find(".td-status").html('<span class="label label-success radius">已启用</span>');
                _obj.remove();
            }, (xhr) => {
                layer.msg('启用失败！' + xhr.responseText, {icon: 2, time: 3000})
            })
        })
    }

    /*用户-编辑*/
    function member_edit(obj) {
        let _obj = $(obj)
        let input_name = $("#member_style input[name$='name']")
        let input_studentID = $("#member_style input[name$='studentID']")
        let input_password = $("#member_style input[name$='password']")
        let td = _obj.parent('td').siblings('td')
        let td_ID = td.eq(1)
        let td_name = td.eq(2)
        let originID = td_ID.text()
        let originName = td_name.text()
        let td_status = td.siblings('.td-status')
        let td_manage = td.parent('tr').find('.td-manage')
        let status = td_status.text()
        let originAdmitted = 0
        if (status === '已启用') {
            originAdmitted = '1'
            $("#member_style input[type='radio']:first").attr('checked', 'checked');
        } else if (status === '已停用') {
            originAdmitted = '-1'
            $("#member_style input[type='radio']:last").attr('checked', 'checked');
        }
        input_name.val(originName)
        input_studentID.val(originID)
        input_password.val('')
        layer.open({
            type: 1,
            title: '修改信息(留空不修改)',
            shadeClose: true, //点击遮罩关闭层
            area: ['280px', ''],
            content: $('#member_style'),
            btn: ['提交', '取消'],
            yes: function (index) {
                let name = input_name.val()
                let studentID = input_studentID.val()
                let password = input_password.val()
                let admitted = $("#member_style input[type='radio']:checked").val();
                if ((name === '' || originName === name) && (studentID === '' || originID === studentID) && password === '' && originAdmitted === admitted) {
                    layer.alert("未修改任何信息！", {
                        title: '提示框',
                        icon: 0,
                    });
                    layer.close(index);
                    return false;
                }
                if (name === '' || originName === name) {
                    name = ''
                }
                if (studentID === '' || originID === studentID) {
                    studentID = ''
                }
                if (password !== '') {
                    password = hex_md5(password)
                }
                if (originAdmitted === admitted) {
                    admitted = ''
                }
                _request('operate/modifyUser.php', 'originID=' + originID + '&name=' + name + '&studentID=' + studentID + '&password=' + password + '&admitted=' + admitted, () => {
                    layer.alert('修改成功！', {
                        title: '提示框',
                        icon: 1,
                    });
                    layer.close(index);
                    if (name !== '') {
                        td.eq(2).text(name)
                    }
                    if (studentID !== '') {
                        td.eq(1).text(studentID)
                    }
                    if (admitted === '1') {
                        td_manage.children('a').eq(0).remove()
                        td_manage.prepend('<a style="text-decoration:none" class="btn btn-xs btn-success" onClick="member_stop(this)" href="javascript:;" title="停用"><i class="icon-ok bigger-120"></i></a>');
                        td_status.html('<span class="label label-success radius">已启用</span>');
                    } else if (admitted === '-1') {
                        td_manage.children('a').eq(0).remove()
                        td_manage.prepend('<a style="text-decoration:none" class="btn btn-xs " onClick="member_start(this)" href="javascript:;" title="启用"><i class="icon-ok bigger-120"></i></a>');
                        td_status.html('<span class="label label-default radius">已停用</span>');
                    }
                }, (xhr) => {
                    layer.alert('错误！' + xhr.responseText, {
                        title: '提示框',
                        icon: 2,
                    });
                })
            }
        });
    }

    /*用户-删除*/
    function member_del(obj) {
        layer.confirm('确认要删除吗？', function () {
            let _obj = $(obj)
            let id = _obj.parent("td").siblings().eq(1).text()
            _request('operate/delUser.php', 'value=' + id, () => {
                _obj.parents("tr").remove()
                layer.msg('已删除!', {icon: 1, time: 2000})
            }, (xhr) => {
                layer.msg('删除失败！' + xhr.responseText, {icon: 2, time: 3000})
            })
        })
    }

    function _request(url, data, success, error) {
        let xhr = new XMLHttpRequest()
        if (window.XMLHttpRequest) {
            xhr = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            xhr = new ActiveXObject("Microsoft.XMLHTTP");
        } else {
            alert('浏览器不支持XMLHttpRequest！')
            return
        }
        xhr.onload = function () {
            let status = xhr.status
            if (status === 200) {
                //success
                success(xhr)
            } else if (status === 422) {
                //error
                error(xhr)
            } else if (status === 401) {
                //Unauthorized
                window.location.href = 'login.php'
            } else {
                //fail
                layer.msg(status + '未知错误！', {icon: 2, time: 3000})
            }
        }
        xhr.timeout = 2000;
        xhr.ontimeout = function () {
            layer.msg('请求服务器超时！', {icon: 2, time: 3000})
        }
        xhr.open("POST", url, true)
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
        xhr.send(data)
    }

</script>