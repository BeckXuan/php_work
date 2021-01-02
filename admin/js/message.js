const limit_title = 40;
const limit_message = 60;
let table, dt, DT
$(document).ready(function () {
    table = $('#sample-table')
    dt = table.dataTable({
        "aaSorting": [[1, "desc"]],//默认第几个排序
        "bStateSave": true,//状态保存
        "aoColumnDefs": [
            //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
            {"orderable": false, "aTargets": [0, 8]}, // 制定列不参与排序
            {
                "targets": 0,
                "render": function () {
                    return '<label><input type="checkbox" class="ace"><span class="lbl"></span></label>'
                }
            }, {
                "targets": 2,
                "render": function (data) {
                    let json = JSON.parse(data)
                    let studentName = json['studentName']
                    if (studentName === null) {
                        studentName = '(该学生不存在)'
                    }
                    return studentName
                }
            }, {
                "targets": 3,
                "sClass": "text-l"
            }, {
                "targets": 4,
                "sClass": "text-l",
                "render": function (data) {
                    let json = JSON.parse(data)
                    let articleTitle = json['articleTitle']
                    if (articleTitle === null) {
                        articleTitle = '(该文章不存在)'
                    }
                    return '<a href="javascript:void(0)" onclick="message_view(this)" title="' + articleTitle + '">' + _substr(articleTitle, limit_title) + '</a>'
                }
            }, {
                "targets": 6,
                "sClass": "text-l",
                "render": function (data) {
                    return '<a href="javascript:void(0)" onclick="message_view(this)" title="' + data + '">' + _substr(data, limit_message) + '</a>'
                }
            }, {
                "targets": -1,
                "sClass": "td-manage",
                "render": function () {
                    return '<a title="编辑" onclick="message_edit(this)" href="javascript:" class="btn btn-xs btn-info"><i class="icon-edit bigger-120"></i></a> <a href="javascript:" onclick="member_del(this)" title="删除" class="btn btn-xs btn-warning"><i class="icon-trash bigger-120"></i></a>'
                }
            }
        ]
    });
    DT = table.DataTable()
    $('table th input:checkbox').on('click', function () {
        let checked = $(this).prop("checked");
        $(this).closest('table').find('tr > td:first-child input:checkbox')
            .each(function () {
                $(this).prop("checked", checked);
            });
    });
    /*留言添加*/
    $('#message_add').on('click', function () {
        let edit_ID = $('#edit_ID')
        let edit_article_id = $('#edit_article_id')
        let edit_message_content = $('#edit_message_content')
        edit_ID.val('')
        $('#edit_name').text('(添加后自动显示)')
        edit_article_id.val('')
        $('#edit_article_title').text('(添加后自动显示)')
        $('#edit_message_id').text('(添加后自动生成)')
        edit_message_content.val('')
        layer.open({
            type: 1,
            title: '留言编辑(留空不修改)',
            shadeClose: false,
            area: ['800px', ''],
            content: $('#message_edit'),
            btn: ['提交', '取消'],
            yes: function (index) {
                let ID = edit_ID.val()
                let articleId = edit_article_id.val()
                let message = edit_message_content.val()
                if (ID === '' || articleId === '' || message === '') {
                    layer.alert("所填信息不能为空！", {
                        title: '提示框',
                        icon: 0,
                    });
                    return false;
                }
                _request('operate/addMessage.php', 'studentID=' + ID + '&articleId=' + articleId + '&message=' + message, (xhr) => {
                    layer.alert('添加成功！', {
                        title: '提示框',
                        icon: 1,
                    });
                    layer.close(index);
                    edit_ID.val('')
                    edit_article_id.val('')
                    edit_message_content.val('')
                    let jsonData = xhr.responseText
                    let json = JSON.parse(jsonData)
                    dt.fnAddData(['', ID, jsonData, articleId, jsonData, json['messageId'], message, json['time'], ''])
                    layer.alert('添加成功！', {title: '提示框', icon: 1})
                }, (xhr) => {
                    layer.alert('错误！' + xhr.responseText, {
                        title: '提示框',
                        icon: 2,
                    });
                })
            }
        })
    })
})

/*留言查看*/
function message_view(obj) {
    let _row = DT.row($(obj).parents("tr"))
    let td = $(obj).parents("tr").children('td')
    $('#view_ID').text(DT.cell(_row, 1).data())
    $('#view_name').text(td.eq(2).text())
    $('#view_article_id').text(DT.cell(_row, 3).data())
    $('#view_article_title').text(td.eq(4).children('a').attr('title'))
    $('#view_message_id').text(DT.cell(_row, 5).data())
    $('#view_message_content').text(DT.cell(_row, 6).data())
    layer.open({
        type: 1,
        title: '留言信息',
        shadeClose: false,
        area: ['500px', ''],
        content: $('#message'),
        btn: ['确定', '取消'],
    });
}

/*留言删除*/
function member_del(obj) {
    let _row = DT.row($(obj).parents("tr"))
    let id = DT.cell(_row, 5).data()
    layer.confirm('确认要删除吗？', function () {
        _request('operate/delMessage.php', 'value=' + id, () => {
            //_obj.parents("tr").remove();
            _row.remove().draw()
            layer.msg('已删除!', {icon: 1, time: 1000});
        }, (xhr) => {
            layer.msg('删除失败！' + xhr.responseText, {icon: 2, time: 3000});
        })

    });
}

/*留言编辑*/
function message_edit(obj) {
    let tr = $(obj).parents("tr")
    let _row = DT.row(tr)
    let td = tr.children('td')
    let originID = DT.cell(_row, 1).data()
    let originArticleId = DT.cell(_row, 3).data()
    let originMessage = DT.cell(_row, 6).data()
    let edit_ID = $('#edit_ID')
    edit_ID.val(originID)
    $('#edit_name').text(td.eq(2).text())
    let edit_article_id = $('#edit_article_id')
    edit_article_id.val(originArticleId)
    $('#edit_article_title').text(td.eq(4).children('a').attr('title'))
    let messageId = DT.cell(_row, 5).data()
    $('#edit_message_id').text(messageId)
    let edit_message_content = $('#edit_message_content')
    edit_message_content.val(originMessage)
    layer.open({
        type: 1,
        title: '留言编辑(留空不修改)',
        shadeClose: false,
        area: ['800px', ''],
        content: $('#message_edit'),
        btn: ['提交', '取消'],
        yes: function (index) {
            let ID = edit_ID.val()
            let articleId = edit_article_id.val()
            let message = edit_message_content.val()
            if (ID === '' || originID === ID) {
                ID = ''
            }
            if (articleId === '' || originArticleId === articleId) {
                articleId = ''
            }
            if (message === '' || originMessage === message) {
                message = ''
            }
            if (ID === '' && articleId === '' && message === '') {
                layer.alert("未修改任何信息！", {
                    title: '提示框',
                    icon: 0,
                });
                layer.close(index);
                return false;
            }
            _request('operate/modifyMessage.php', 'messageId=' + messageId + '&studentID=' + ID + '&articleId=' + articleId + '&message=' + message, (xhr) => {
                layer.alert('修改成功！', {
                    title: '提示框',
                    icon: 1,
                });
                layer.close(index);
                let jsonData = xhr.responseText
                if (ID !== '') {
                    DT.cell(_row, 1).data(ID).draw()
                    DT.cell(_row, 2).data(jsonData)
                }
                if (articleId !== '') {
                    DT.cell(_row, 3).data(articleId)
                    DT.cell(_row, 4).data(jsonData)
                }
                if (message !== '') {
                    DT.cell(_row, 6).data(message)
                }
                DT.draw()
            }, (xhr) => {
                layer.alert('错误！' + xhr.responseText, {
                    title: '提示框',
                    icon: 2,
                });
            })
        }
    })
}