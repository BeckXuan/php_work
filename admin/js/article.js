const limit_title = 40
const limit_content = 60
let table, dt, DT
$(document).ready(function () {
    table = $('#sample-table')
    dt = table.dataTable({
        "aaSorting": [[1, "asc"]],//默认第几个排序
        "bStateSave": false,//状态保存
        "aoColumnDefs": [
            {"orderable": false, "aTargets": [0, 5]},// 制定列不参与排序
            {
                "targets": 0,
                "render": function () {
                    return '<label><input type="checkbox" class="ace"><span class="lbl"></span></label>'
                }
            }, {
                "targets": 2,
                "render": function (data) {
                    return '<a href="javascript:void(0)" onclick="article_view(this)" title="' + data + '">' + _substr(data, limit_title) + '</a>'
                }
            }, {
                "targets": 3,
                "render": function (data) {
                    return '<a href="javascript:void(0)" onclick="article_view(this)" title="' + data + '">' + _substr(data, limit_content) + '</a>'
                }
            }, {
                "targets": 5,
                "sClass": "td-manage",
                "render": function () {
                    return '<a title="编辑" onclick="article_edit(this)" href="javascript:" class="btn btn-xs btn-info"><i class="icon-edit bigger-120"></i></a> <a href="javascript:" onclick="article_del(this)" title="删除" class="btn btn-xs btn-warning"><i class="icon-trash bigger-120"></i></a>'
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
    /*文章添加*/
    $('#article_add').on('click', function () {
        $('#edit_id').text('(添加后自动生成)')
        $('#edit_time').text('(添加后自动生成)')
        let edit_title = $('#edit_title')
        let edit_content = $('#edit_content')
        edit_title.val('')
        edit_content.val('')
        layer.open({
            type: 1,
            title: '文章编辑(留空不修改)',
            shadeClose: false,
            area: ['800px', ''],
            content: $('#article_edit'),
            btn: ['提交', '取消'],
            yes: function (index) {
                let title = edit_title.val()
                let content = edit_content.val()
                if (title === '' || content === '') {
                    layer.alert("所填信息不能为空！", {
                        title: '提示框',
                        icon: 0,
                    });
                    return false;
                }
                _request('operate/addArticle.php', 'title=' + title + '&content=' + content, () => {
                    layer.close(index);
                    edit_title.val('')
                    edit_content.val('')
                    layer.alert('添加成功！(刷新后显示)', {title: '提示框', icon: 1})
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

/*文章删除*/
function article_del(obj) {
    let _obj = $(obj)
    let id = _obj.parent("td").siblings().eq(1).text()
    layer.confirm('确认要删除吗？', function () {
        _request('operate/delArticle.php', 'value=' + id, () => {
            //_obj.parents("tr").remove();
            DT.row(_obj.parents("tr")).remove().draw()
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
        area: ['800px', ''],
        content: $('#article'),
        btn: ['确定']
    })
}

/*文章编辑*/
function article_edit(obj) {
    let td = $(obj).parents("tr").children('td')
    let id = td.eq(1).text()
    $('#edit_id').text(id)
    $('#edit_time').text(td.eq(4).text())
    let a_title = td.eq(2).children('a')
    let a_content = td.eq(3).children('a')
    let edit_title = $('#edit_title')
    let edit_content = $('#edit_content')
    let originTitle = a_title.attr('title')
    let originContent = a_content.attr('title')
    edit_title.val(originTitle)
    edit_content.val(originContent)
    layer.open({
        type: 1,
        title: '文章编辑(留空不修改)',
        shadeClose: false,
        area: ['800px', ''],
        content: $('#article_edit'),
        btn: ['提交', '取消'],
        yes: function (index) {
            let title = edit_title.val()
            let content = edit_content.val()
            if (title === '' || originTitle === title) {
                title = ''
            }
            if (content === '' || originContent === content) {
                content = ''
            }
            if (title === '' && content === '') {
                layer.alert("未修改任何信息！", {
                    title: '提示框',
                    icon: 0,
                });
                layer.close(index);
                return false;
            }
            _request('operate/modifyArticle.php', 'id=' + id + '&title=' + title + '&content=' + content, () => {
                layer.alert('修改成功！', {
                    title: '提示框',
                    icon: 1,
                });
                layer.close(index);
                if (title !== '') {
                    a_title.text(_substr(title, limit_title))
                    a_title.attr('title', title)
                }
                if (content !== '') {
                    a_content.text(_substr(content, limit_content))
                    a_content.attr('title', content)
                }
            }, (xhr) => {
                layer.alert('错误！' + xhr.responseText, {
                    title: '提示框',
                    icon: 2,
                });
            })
        }
    })
}