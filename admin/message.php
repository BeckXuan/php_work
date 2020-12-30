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
    <title>意见反馈</title>
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
    <div class="Feedback_style">
        <div class="border clearfix">
       <span class="l_f">
           <a href="javascript:void()" class="btn btn-danger"><i class="icon-trash"></i>&nbsp;批量删除</a>
       </span>
            <span class="r_f">共：<b>2334</b>条</span>
        </div>
        <div class="feedback">
            <table class="table table-striped table-bordered table-hover" id="sample-table">
                <thead>
                <tr>
                    <th width="25px"><label><input type="checkbox" class="ace"><span class="lbl"></span></label></th>
                    <th width="120px">学号</th>
                    <th width="120px">用户名</th>
                    <th width="200px">文章标题</th>
                    <th width="">留言内容</th>
                    <th width="200px">时间</th>
                    <th width="250">操作</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><label><input type="checkbox" class="ace"><span class="lbl"></span></label></td>
                    <td>1</td>
                    <td>张小泉</td>
                    <td class="text-l">
                        <a href="javascript:void(0)" onclick="Guestbook_iew('12')">这是文章标题</a>
                    </td>
                    <td class="text-l">
                        <a href="javascript:void(0)" onclick="Guestbook_iew('12')">“第二届中国无锡水蜜桃开摘节”同时开幕，为期三个月的蜜桃季全面启动。值此京东“618品质狂欢节”之际，中国特产无锡馆限量上线618份8只装精品水蜜桃，61.8元全国包邮限时抢购。为了保证水蜜桃从枝头到达您的手中依旧鲜甜如初，京东采用递送升级服务，从下单到包装全程冷链运输。</a>
                    </td>
                    <td>2016-6-11 11:11:42</td>
                    <td class="td-manage">
                        <a title="编辑" onclick="member_edit(this)" href="javascript:"
                           class="btn btn-xs btn-info"><i class="icon-edit bigger-120"></i></a>
                        <a href="javascript:void(0)" onclick="member_del(this)" title="删除"
                           class="btn btn-xs btn-warning"><i
                                    class="icon-trash bigger-120"></i></a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!--留言详细-->
<div id="Guestbook" style="display:none">
    <div class="content_style">
        <div class="form-group"><span class="col-sm-2 control-label no-padding-right">留言用户 </span>
            <div class="col-sm-9">胡海天堂</div>
        </div>
        <div class="form-group"><span class="col-sm-2 control-label no-padding-right">用户学号 </span>
            <div class="col-sm-9">01</div>
        </div>
        <div class="form-group"><span class="col-sm-2 control-label no-padding-right">留言内容 </span>
            <div class="col-sm-9">
                三年同窗，一起沐浴了一片金色的阳光，一起度过了一千个日夜，我们共同谱写了多少友谊的篇章?愿逝去的那些闪亮的日子，都化作美好的记忆，永远留在心房。认识您，不论是生命中的一段插曲，还是永久的知已，我都会珍惜，当我疲倦或老去，不再拥有青春的时候，这段旋律会滋润我生命的每一刻。在此我只想说：有您真好!无论你身在何方，我的祝福永远在您身边!
            </div>
        </div>

    </div>
</div>
</body>
</html>
<script type="text/javascript">
    /*留言查看*/
    function Guestbook_iew() {
        layer.open({
            type: 1,
            title: '反馈信息',
            shadeClose: false,
            area: ['600px', ''],
            content: $('#Guestbook'),
            btn: ['确定', '取消'],
        });
    }

    /*留言-删除*/
    function member_del(obj) {
        layer.confirm('确认要删除吗？', function () {
            $(obj).parents("tr").remove();
            layer.msg('已删除!', {icon: 1, time: 1000});
        });
    }

    jQuery(function ($) {
        $('#sample-table').dataTable({
            "aaSorting": [[1, "desc"]],//默认第几个排序
            "bStateSave": true,//状态保存
            "aoColumnDefs": [
                //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
                {"orderable": false, "aTargets": [0, 6]}// 制定列不参与排序
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
</script>