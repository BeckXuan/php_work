let table, dt, DT
$(document).ready(function ($) {
    table = $('#sample-table')
    dt = table.dataTable({
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
                    let status = data === '0' ? '待审核' : (data === '1' ? '已启用' : '已停用')
                    let _class = data === '1' ? 'label-success' : 'label-default';
                    return '<span class="label ' + _class + ' radius">' + status + '</span>'
                }
            }, {
                "targets": 5,
                "sClass": "td-manage",
                "render": function (data) {
                    let title = data !== '1' ? '启用' : '停用'
                    let callback = data !== '1' ? 'start' : 'stop'
                    let _class = data === '1' ? 'btn-success' : ''
                    return '<a onClick="member_' + callback + '(this)" href="javascript:void(0)" title="' + title + '" class="btn btn-xs ' + _class + '"><i class="icon-ok bigger-120"></i></a> <a title="编辑" onclick="member_edit(this)" href="javascript:" class="btn btn-xs btn-info"><i class="icon-edit bigger-120"></i></a> <a title="删除" href="javascript:" onclick="member_del(this)" class="btn btn-xs btn-warning"><i class="icon-trash  bigger-120"></i></a>'
                }
            }
        ]
    })
    DT = table.DataTable()
    $('table th input:checkbox').on('click', function () {
        let checked = $(this).prop("checked");
        $(this).closest('table').find('tr > td:first-child input:checkbox')
            .each(function () {
                $(this).prop("checked", checked);
            });
    });
    /*用户添加*/
    $('#member_add').on('click', function () {
        let input_name = $("#member_edit input[name$='name']")
        let input_studentID = $("#member_edit input[name$='studentID']")
        let input_password = $("#member_edit input[name$='password']")
        input_name.val('')
        input_studentID.val('')
        input_password.val('')
        $("#member_edit input[type='radio']:first").prop('checked', 'checked');
        layer.open({
            type: 1,
            title: '添加用户',
            shadeClose: false, //点击遮罩关闭层
            area: ['280px', '370px'],
            content: $('#member_edit'),
            btn: ['提交', '取消'],
            yes: function (index) {
                let name = input_name.val()
                let studentID = input_studentID.val()
                let password = input_password.val()
                let admitted = $("#member_edit input[type='radio']:checked").val();
                if (name === '' || studentID === '' || password === '') {
                    layer.alert("所填信息不能为空！", {
                        title: '提示框',
                        icon: 0,
                    });
                    return false;
                }
                password = hex_md5(password)
                _request('operate/addUser.php', 'studentID=' + studentID + '&name=' + name + '&password=' + password + '&admitted=' + admitted, (xhr) => {
                    layer.close(index);
                    input_name.val('')
                    input_studentID.val('')
                    input_password.val('')
                    let json = JSON.parse(xhr.responseText)
                    dt.fnAddData(['', studentID, name, json['time'], admitted, admitted, ''])
                    dt.fnAdjustColumnSizing()
                    layer.alert('添加成功！', {title: '提示框', icon: 1})
                }, (xhr) => {
                    layer.alert('添加失败！' + xhr.responseText, {title: '提示框', icon: 2})
                })
            }
        });
    });
})

/*用户删除*/
function member_del(obj) {
    layer.confirm('确认要删除吗？', function () {
        let _row = DT.row($(obj).parents("tr"))
        let id = DT.cell(_row, 1).data()
        _request('operate/delUser.php', 'value=' + id, () => {
            //_obj.parents("tr").remove();
            _row.remove().draw()
            dt.fnAdjustColumnSizing()
            layer.msg('已删除!', {icon: 1, time: 2000})
        }, (xhr) => {
            layer.msg('删除失败！' + xhr.responseText, {icon: 2, time: 3000})
        })
    })
}

/*用户停用*/
function member_stop(obj) {
    layer.confirm('确认要停用吗？', function () {
        let _row = DT.row($(obj).parents("tr"))
        let id = DT.cell(_row, 1).data()
        _request('operate/denyUser.php', 'value=' + id, () => {
            layer.msg('已停用!', {icon: 1, time: 2000});
            DT.cell(_row, -2).data('-1')
            DT.cell(_row, -1).data('-1')
        }, (xhr) => {
            layer.msg('停用失败！' + xhr.responseText, {icon: 2, time: 3000})
        })
    });
}

/*用户启用*/
function member_start(obj) {
    layer.confirm('确认要启用吗？', function () {
        let _row = DT.row($(obj).parents("tr"))
        let id = DT.cell(_row, 1).data()
        _request('operate/admitUser.php', 'value=' + id, () => {
            layer.msg('已启用!', {icon: 1, time: 2000})
            DT.cell(_row, -2).data('1')
            DT.cell(_row, -1).data('1')
        }, (xhr) => {
            layer.msg('启用失败！' + xhr.responseText, {icon: 2, time: 3000})
        })
    })
}

/*用户编辑*/
function member_edit(obj) {
    let _row = DT.row($(obj).parents("tr"))
    let input_name = $("#member_edit input[name$='name']")
    let input_studentID = $("#member_edit input[name$='studentID']")
    let input_password = $("#member_edit input[name$='password']")
    let originID = DT.cell(_row, 1).data()
    let originName = DT.cell(_row, 2).data()
    let originAdmitted = DT.cell(_row, -1).data()
    $("#member_edit input[value='" + originAdmitted + "']").prop('checked', 'checked');
    input_name.val(originName)
    input_studentID.val(originID)
    input_password.val('')
    layer.open({
        type: 1,
        title: '修改信息(留空不修改)',
        shadeClose: false, //点击遮罩关闭层
        area: ['280px', '370px'],
        content: $('#member_edit'),
        btn: ['提交', '取消'],
        yes: function (index) {
            let name = input_name.val()
            let studentID = input_studentID.val()
            let password = input_password.val()
            let admitted = $("#member_edit input[type='radio']:checked").val();
            if (name === '' || originName === name) {
                name = ''
            }
            if (studentID === '' || originID === studentID) {
                studentID = ''
            }
            if (originAdmitted === admitted) {
                admitted = ''
            }
            if (name === '' && studentID === '' && admitted === '' && password === '') {
                layer.alert("未修改任何信息！", {
                    title: '提示框',
                    icon: 0,
                });
                layer.close(index);
                return false;
            }
            password = hex_md5(password)
            _request('operate/modifyUser.php', 'originID=' + originID + '&name=' + name + '&studentID=' + studentID + '&password=' + password + '&admitted=' + admitted, () => {
                layer.alert('修改成功！', {
                    title: '提示框',
                    icon: 1,
                });
                layer.close(index);
                if (studentID !== '') {
                    DT.cell(_row, 1).data(studentID)
                }
                if (name !== '') {
                    DT.cell(_row, 2).data(name)
                }
                if (admitted !== '') {
                    DT.cell(_row, -2).data(admitted)
                    DT.cell(_row, -1).data(admitted)
                }
                dt.fnAdjustColumnSizing()
            }, (xhr) => {
                let json = JSON.parse(xhr.responseText)
                let modified = json['modified']
                let successMsg = ''
                let failureMsg = ''
                if (modified['studentID']) {
                    originID = studentID
                    DT.cell(_row, 1).data(studentID)
                    successMsg += '修改学号成功！</br>'
                } else if (studentID !== '') {
                    failureMsg += '修改学号失败！</br>'
                }
                if (modified['name']) {
                    originName = name
                    DT.cell(_row, 2).data(name)
                    successMsg += '修改用户名成功！</br>'
                } else if (name !== '') {
                    failureMsg += '修改用户名失败！</br>'
                }
                if (modified['admitted']) {
                    originAdmitted = admitted
                    DT.cell(_row, -2).data(admitted)
                    DT.cell(_row, -1).data(admitted)
                    successMsg += '修改状态成功！</br>'
                } else if (admitted !== '') {
                    failureMsg += '修改状态失败！</br>'
                }
                if (modified['password']) {
                    input_password.val('')
                    successMsg += '修改密码成功！</br>'
                }
                dt.fnAdjustColumnSizing()
                layer.alert('成功：</br>' + successMsg + '</br>失败：</br>' + failureMsg + '</br>返回消息：</br>' + json['error'], {
                    title: '提示框',
                    icon: 5,
                });
            })
        }
    });
}
