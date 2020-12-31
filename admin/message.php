<?php
require "../common.php";
session_start();
//if (!isAdminLegal()) {
//    header('location: login.php');
//    return;
//}
$db = &DB::getInstance();
$db->initMessageInformation(0, 100);
?>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8"/>
    <title>留言列表</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/style.css"/>
    <link rel="stylesheet" href="assets/css/ace.min.css"/>
    <link rel="stylesheet" href="assets/css/font-awesome.min.css"/>
    <script src="js/jquery-1.9.1.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/jquery.dataTables.bootstrap.js"></script>
    <script src="assets/layer/layer.js" type="text/javascript"></script>
</head>

<body>
<div class="margin clearfix">
    <div>
        <div class="border clearfix">
       <span class="l_f">
           <a href="javascript:void(0)" id="article_add" class="btn btn-warning"><i class="icon-plus"></i>&nbsp;添加留言</a>
       </span>
            <span class="r_f">共：<b><?= $db->getNrOfMessages() ?></b>条</span>
        </div>
        <div class="feedback">
            <table class="table table-striped table-bordered table-hover" id="sample-table">
                <thead>
                <tr>
                    <th><label><input type="checkbox" class="ace"><span class="lbl"></span></label></th>
                    <th>学号</th>
                    <th>用户名</th>
                    <th>文章ID</th>
                    <th>文章标题</th>
                    <th>留言ID</th>
                    <th>留言内容</th>
                    <th>时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($message = $db->getNextMessage()) {
                    $studentID = $message->getStudentID();
                    $studentName = $db->getUserName($studentID);
                    if ($studentName === null) {
                        $studentName = '(该学生已被删除)';
                    }
                    $articleId = $message->getArticleId();
                    $articleTitle = $db->getArticleTitle($articleId);
                    $titleLimit = 20;
                    $subArticleTitle = null;
                    if ($articleTitle === null) {
                        $subArticleTitle = $articleTitle = '(该文章已被删除)';
                    } else if (mb_strlen($articleTitle) > $titleLimit) {
                        $subArticleTitle = mb_substr($articleTitle, 0, $titleLimit) . '...';
                    } else {
                        $subArticleTitle = $articleTitle;
                    }
                    $messageId = $message->getId();
                    $messageContent = $message->getMessage();
                    $messageLimit = 30;
                    $subMessageContent = null;
                    if (mb_strlen($messageContent) > $messageLimit) {
                        $subMessageContent = mb_substr($messageContent, 0, $messageLimit);
                    } else {
                        $subMessageContent = $messageContent;
                    }
                    $time = $message->getTime();
                    echo <<< tr
                <tr>
                    <td><label><input type="checkbox" class="ace"><span class="lbl"></span></label></td>
                    <td>{$studentID}</td>
                    <td>{$studentName}</td>
                    <td class="text-l">{$articleId}</td>
                    <td class="text-l">
                        <a href="javascript:void(0)" onclick="message_view(this)" title="{$articleTitle}">{$subArticleTitle}</a>
                    </td>
                    <td>{$messageId}</td>
                    <td class="text-l">
                        <a href="javascript:void(0)" onclick="message_view(this)" title="{$messageContent}">{$subMessageContent}</a>
                    </td>
                    <td>{$time}</td>
                    <td class="td-manage"></td>
                </tr>

tr;
                } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!--留言详细-->
<div id="message" style="display:none">
    <div class="content_style">
        <div class="form-group"><span class="col-sm-2 control-label no-padding-right">用户学号 </span>
            <div class="col-sm-9" id="view_ID"></div>
        </div>
        <div class="form-group"><span class="col-sm-2 control-label no-padding-right">留言用户 </span>
            <div class="col-sm-9" id="view_name"></div>
        </div>
        <div class="form-group"><span class="col-sm-2 control-label no-padding-right">文章id </span>
            <div class="col-sm-9" id="view_article_id"></div>
        </div>
        <div class="form-group"><span class="col-sm-2 control-label no-padding-right">文章标题 </span>
            <div class="col-sm-9" id="view_article_title"></div>
        </div>
        <div class="form-group"><span class="col-sm-2 control-label no-padding-right">留言内容 </span>
            <div class="col-sm-9" id="view_message_id"></div>
        </div>
        <div class="form-group"><span class="col-sm-2 control-label no-padding-right">留言内容 </span>
            <div class="col-sm-9" id="view_message_content"></div>
        </div>

    </div>
</div>
</body>
</html>
<script type="text/javascript">
    $(document).ready(function () {
        $('#sample-table').dataTable({
            "aaSorting": [[1, "desc"]],//默认第几个排序
            "bStateSave": true,//状态保存
            "aoColumnDefs": [
                //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
                {"orderable": false, "aTargets": [0, 8]}, // 制定列不参与排序
                {
                    "targets": 8,
                    "render": function () {
                        return '<a title="编辑" onclick="article_edit(this)" href="javascript:" class="btn btn-xs btn-info"><i class="icon-edit bigger-120"></i></a> <a href="javascript:" onclick="member_del(this)" title="删除" class="btn btn-xs btn-warning"><i class="icon-trash bigger-120"></i></a>'
                    }
                }
            ]
        });
        $('table th input:checkbox').on('click', function () {
            let that = this;
            $(this).closest('table').find('tr > td:first-child input:checkbox')
                .each(function () {
                    this.checked = that.checked;
                    $(this).closest('tr').toggleClass('selected');
                });
        });
    })

    /*文章查看*/
    function article_view(obj) {

    }

    /*留言查看*/
    function message_view(obj) {
        let td = $(obj).parents("tr").children('td')
        $('#view_ID').text(td.eq(1).text())
        $('#view_name').text(td.eq(2).text())
        $('#view_article_id').text(td.eq(3).text())
        $('#view_article_title').text(td.eq(4).children('a').attr('title'))
        $('#view_message_id').text(td.eq(5).text())
        $('#view_message_content').text(td.eq(6).children('a').attr('title'))
        layer.open({
            type: 1,
            title: '留言信息',
            shadeClose: false,
            area: ['600px', ''],
            content: $('#message'),
            btn: ['确定', '取消'],
        });
    }

    /*留言-删除*/
    function member_del(obj) {
        let _obj = $(obj)
        let id = _obj.parent("td").siblings().eq(5).text()
        layer.confirm('确认要删除吗？', function () {
            _request('operate/delMessage.php', 'value=' + id, () => {
                _obj.parents("tr").remove();
                layer.msg('已删除!', {icon: 1, time: 1000});
            }, (xhr) => {
                layer.msg('删除失败！' + xhr.responseText, {icon: 2, time: 3000});
            })

        });
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