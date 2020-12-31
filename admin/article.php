<?php
require "../common.php";
session_start();
//if (!isAdminLegal()) {
//    header('location: login.php');
//    return;
//}
$db = &DB::getInstance();
$db->initArticleInformation(0, 100);
?>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8"/>
    <title>文章列表</title>
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
        <a href="javascript:void(0)" id="article_add" class="btn btn-warning"><i class="icon-plus"></i>&nbsp;添加文章</a>
       </span>
            <span class="r_f">原始共：<b><?= $db->getNrOfArticles() ?></b>条</span>
        </div>
        <!--文章列表-->
        <div class="article_list">
            <table class="table table-striped table-bordered table-hover" id="sample-table">
                <thead>
                <tr>
                    <th><label><input type="checkbox" class="ace"><span class="lbl"></span></label></th>
                    <th>文章ID</th>
                    <th>文章标题</th>
                    <th>文章内容</th>
                    <th>时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($article = $db->getNextArticle()) {
                    $id = $article->getId();
                    $title = $article->getTitle();
                    $content = $article->getContent();
                    $time = $article->getTime();
                    $titleLimit = 20;
                    $contentLimit = 30;
                    $subTitle = null;
                    $subContent = null;
                    if (mb_strlen($title) > $titleLimit) {
                        $subTitle = mb_substr($title, 0, $titleLimit) . '...';
                    } else {
                        $subTitle = $title;
                    }
                    if (mb_strlen($content) > $contentLimit) {
                        $subContent = mb_substr($content, 0, $contentLimit) . '...';
                    } else {
                        $subContent = $content;
                    }
                    echo <<< tr
                <tr>
                    <td></td>
                    <td>{$id}</td>
                    <td>
                        <a href="javascript:void(0)" onclick="article_view(this)" title="{$title}">{$subTitle}</a>
                    </td>
                    <td>
                        <a href="javascript:void(0)" onclick="article_view(this)" title="{$content}">{$subContent}</a>
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

<!--详细-->
<div id="article" style="display:none">
    <div class="content_style">
        <div class="form-group"><label class="col-sm-2 control-label no-padding-right">文章ID </label>
            <div class="col-sm-9" id="view_id">这是文章ID</div>
        </div>
        <div class="form-group"><label class="col-sm-2 control-label no-padding-right">添加时间 </label>
            <div class="col-sm-9" id="view_time">这是添加时间</div>
        </div>
        <div class="form-group"><label class="col-sm-2 control-label no-padding-right">文章标题 </label>
            <div class="col-sm-9" id="view_title">这是文章标题</div>
        </div>
        <div class="form-group"><label class="col-sm-2 control-label no-padding-right">文章内容 </label>
            <div class="col-sm-9" id="view_content">这是文章内容</div>
        </div>
    </div>
</div>
</body>
</html>
<script type="text/javascript">
    $(document).ready(function () {
        $('#sample-table').dataTable({
            "aaSorting": [[1, "asc"]],//默认第几个排序
            "bStateSave": false,//状态保存
            "aoColumnDefs": [
                {"orderable": false, "aTargets": [0, 5]},// 制定列不参与排序
                {
                    "targets": 0,
                    "render": function () {
                        return '<label><input type="checkbox" class="ace"><span class="lbl"></span></label>'
                    }
                },
                {
                    "targets": 5,
                    "render": function () {
                        return '<a title="编辑" onclick="article_edit(this)" href="javascript:" class="btn btn-xs btn-info"><i class="icon-edit bigger-120"></i></a> <a href="javascript:" onclick="member_del(this)" title="删除" class="btn btn-xs btn-warning"><i class="icon-trash bigger-120"></i></a>'
                    }
                }
            ]
        });
        $('table th input:checkbox').on('click', function () {
            let checked = $(this).prop("checked");
            $(this).closest('table').find('tr > td:first-child input:checkbox')
                .each(function () {
                    $(this).prop("checked", checked);
                });
        });
    })

    /*文章-删除*/
    function member_del(obj) {
        let _obj = $(obj)
        let id = _obj.parent("td").siblings().eq(1).text()
        layer.confirm('确认要删除吗？', function () {
            _request('operate/delArticle.php', 'value=' + id, () => {
                //_obj.parents("tr").remove();
                $('#sample-table').DataTable().row(_obj.parents("tr")).remove().draw()
                layer.msg('已删除！', {icon: 1, time: 2000});
            }, (xhr) => {
                layer.msg('删除失败！' + xhr.responseText, {icon: 2, time: 3000});
            })
        });
    }

    /*文章查看*/
    function article_view(obj) {
        let td = $(obj).parents("tr").children('td')
        $('#view_id').text(td.eq(1).text())
        $('#view_title').text(td.eq(2).children('a').attr('title'))
        $('#view_content').html('<p style="text-indent:2em">' + td.eq(3).children('a').attr('title').replaceAll('\n', '</p><p style="text-indent:2em">') + '</p>')
        $('#view_time').text(td.eq(4).text())
        layer.open({
                type: 1,
                title: '文章信息',
                shadeClose: false,
                area: ['600px', ''],
                content: $('#article'),
                btn: ['确定'],
            }
        )
    }

    /*文章编辑*/
    function article_edit(obj) {
        let id = $(obj).parent("td").siblings().eq(1).text()

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
