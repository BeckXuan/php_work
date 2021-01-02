$(document).ready(function ($) {
    $('#sample-table').dataTable({
        "aaSorting": [[1, "asc"]],//默认第几个排序
        "bStateSave": true,//状态保存
        "aoColumnDefs": [{"orderable": false, "aTargets": [0, 4, 5]},
            {
                "targets": 0,
                "render": function () {
                    return '<label><input type="checkbox" class="ace"><span class="lbl"></span></label>'
                }
            }, {
                "targets": 4,
                "sClass": "td-status",
                "render": function (data) {
                    //<span class="label {$class_s} radius">{$status}</span>
                    let json = JSON.parse(data)
                    let status = !json['audited'] ? '待审核' : (json['admitted'] ? '已启用' : '已停用')
                    let _class = json['admitted'] ? 'label-success' : 'label-default';
                    return '<span class="label ' + _class + ' radius">' + status + '</span>'
                }
            }, {
                "targets": 5,
                "sClass": "td-manage",
                "render": function (data) {
                    let json = JSON.parse(data)
                    let admitted = json['admitted']
                    let title = admitted ? '启用' : '停用'
                    let callback = admitted ? 'stop' : 'start'
                    let _class = admitted ? 'btn-success' : ''
                    return '<a onClick="member_' + callback + '(this)" href="javascript:" title="' + title + '" class="btn btn-xs ' + _class + '"><i class="icon-ok bigger-120"></i></a> <a title="编辑" onclick="member_edit(this)" href="javascript:" class="btn btn-xs btn-info"><i class="icon-edit bigger-120"></i></a> <a title="删除" href="javascript:" onclick="member_del(this)" class="btn btn-xs btn-warning"><i class="icon-trash  bigger-120"></i></a>'
                }
            }
        ]
    })
    $('table th input:checkbox').on('click', function () {
        let checked = $(this).prop("checked");
        $(this).closest('table').find('tr > td:first-child input:checkbox')
            .each(function () {
                $(this).prop("checked", checked);
            });
    });
    /*用户添加*/
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
})

/*用户停用*/
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

/*用户启用*/
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
        $("#member_style input[value='1']").prop('checked', 'checked');
    } else if (status === '已停用') {
        originAdmitted = '-1'
        $("#member_style input[value='-1']").prop('checked', 'checked');
    }
    input_name.val(originName)
    input_studentID.val(originID)
    input_password.val('')
    layer.open({
        type: 1,
        title: '修改信息(留空不修改)',
        shadeClose: false, //点击遮罩关闭层
        area: ['280px', ''],
        content: $('#member_style'),
        btn: ['提交', '取消'],
        yes: function (index) {
            let name = input_name.val()
            let studentID = input_studentID.val()
            let password = input_password.val()
            let admitted = $("#member_style input[type='radio']:checked").val();
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
            if (name === '' && studentID === '' && password === '' && admitted === '') {
                layer.alert("未修改任何信息！", {
                    title: '提示框',
                    icon: 0,
                });
                layer.close(index);
                return false;
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
            //_obj.parents("tr").remove();
            $('#sample-table').DataTable().row(_obj.parents("tr")).remove().draw()
            layer.msg('已删除!', {icon: 1, time: 2000})
        }, (xhr) => {
            layer.msg('删除失败！' + xhr.responseText, {icon: 2, time: 3000})
        })
    })
}