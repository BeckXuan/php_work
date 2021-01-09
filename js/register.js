let reg_form = document.getElementById('register')
let eye = document.getElementById("passwordEye")
let reg_inputs = {
    'name': reg_form['name'],
    'studentID': reg_form['studentID'],
    'password': reg_form['password'],
    'btn_reg': reg_form['btn_reg'],
    'btn_rst': reg_form['btn_rst']
}
let reg_sps = {
    'name': document.getElementById('sp_name'),
    'studentID': document.getElementById('sp_studentID')
}
let timeId = {
    'name': null,
    'studentID': null
}
let XHR = {
    'name': null,
    'studentID': null,
    'submit': null
}
let sending = {
    'name': false,
    'studentID': false,
}

let tips = {
    'name': null,
    'studentID': null
}

for (let key in reg_sps) {
    reg_inputs[key].addEventListener('input', () => {
        validate(key)
    }, false)
}
reg_form.addEventListener('reset', regResetAll)
reg_form.addEventListener('submit', _register)
eye.addEventListener('click', changeVisibility)

function validate(type) {
    if (timeId[type]) {
        clearTimeout(timeId[type])
        timeId[type] = null
    }
    if (sending[type]) {
        XHR[type].abort()
        sending[type] = false
    }
    let doc_text = reg_inputs[type]
    let sp = reg_sps[type]
    if (!doc_text.validity.valueMissing && !doc_text.validity.customError) {
        doc_text.setCustomValidity('准备检查是否可用...')
    }
    timeId[type] = setTimeout(() => {
        timeId[type] = null
        sp.className = ''
        if (doc_text.validity.valueMissing) {
            doc_text.className = ''
            doc_text.setCustomValidity('')
            layer.close(tips[type])
            return
        }
        let value = doc_text.value
        doc_text.className = 'input-check-process'
        let _message = '检查是否可用中...'
        doc_text.setCustomValidity(_message)
        layer.close(tips[type])
        tips[type] = layer.tips(_message, doc_text, {
            tips: [2, '#FFD700'],
            tipsMore: true,
            time: 0
        })
        let xhr;
        if (XHR[type]) {
            xhr = XHR[type]
        } else {
            if (window.XMLHttpRequest) {
                xhr = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                xhr = new ActiveXObject("Microsoft.XMLHTTP");
            } else {
                layer.msg('浏览器不支持XMLHttpRequest！', {icon: 2, time: 3000})
                return
            }
            XHR[type] = xhr
            xhr.onload = function () {
                layer.close(tips[type])
                let _status = xhr.status
                let _message = xhr.responseText
                if (_status === 200) {
                    doc_text.className = ''
                    doc_text.setCustomValidity('')
                    tips[type] = layer.tips(_message, doc_text, {
                        tips: [2, '#90EE90'],
                        tipsMore: true,
                        time: 0
                    })
                    return
                }
                if (_status !== 406) {
                    _message = _status + '未知错误！'
                }
                doc_text.className = 'input-check-error'
                sp.className = 'span-check-error'
                doc_text.setCustomValidity(_message)
                tips[type] = layer.tips(_message, doc_text, {
                    tips: [2, '#cb2341'],
                    tipsMore: true,
                    time: 0
                })
                sending[type] = false
            }
            xhr.timeout = 2000;
            xhr.ontimeout = function () {
                let _message = '请求服务器超时！'
                doc_text.setCustomValidity(_message)
                layer.close(tips[type])
                tips[type] = layer.tips(_message, doc_text, {
                    tips: [2, '#FFD700'],
                    tipsMore: true,
                    time: 0
                })
                layer.msg(_message, {icon: 2, time: 3000})
                sending[type] = false
            }
        }
        xhr.open("POST", 'check/' + type + '.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send('value=' + value);
        sending[type] = true
    }, 500)
}

function validateAll() {
    for (let key in reg_sps) {
        validate(key)
    }
}

function regResetAll() {
    for (let key in reg_sps) {
        reg_inputs[key].className = ''
        reg_inputs[key].setCustomValidity('')
        reg_sps[key].className = ''
        layer.closeAll('tips')
    }
}

function changeVisibility() {
    eye.classList.toggle("visible")
    let input_password = reg_inputs['password']
    if (input_password.type === 'text') {
        input_password.type = 'password'
    } else {
        input_password.type = 'text'
    }
}

function _register(e) {
    e.preventDefault()
    let btn_reg = reg_inputs['btn_reg']
    let btn_rst = reg_inputs['btn_rst']
    btn_rst.disabled = true
    btn_reg.disabled = true
    btn_reg.innerText = '注 册 中...'
    let xhr
    if (XHR['submit']) {
        xhr = XHR['submit']
    } else {
        xhr = new XMLHttpRequest();
        if (!xhr) {
            layer.msg('浏览器不支持xhr！', {icon: 2, time: 3000})
            return
        }
        xhr.onload = function () {
            let _status = xhr.status
            let _message = xhr.responseText
            if (_status === 200) {
                layer.msg('注册成功！请等待管理员审核后登录！', {icon: 1, time: 3000})
                reg_form.reset()
                layer.closeAll('tips')
                document.querySelector('.content').classList.remove('s--signup')
                return
            }
            if (_status !== 406) {
                _message = _status + '未知错误！'
            }
            layer.msg(_message, {icon: 2, time: 3000})
            btn_reg.innerText = '注 册'
            btn_reg.disabled = false
            btn_rst.disabled = false
        }
        xhr.timeout = 2000;
        xhr.ontimeout = function () {
            layer.msg('请求服务器超时！', {icon: 2, time: 3000})
            btn_reg.innerText = '注 册'
            btn_reg.disabled = false
            btn_rst.disabled = false
        }
    }
    let name = reg_inputs['name'].value
    let studentID = reg_inputs['studentID'].value
    let password = hex_md5(reg_inputs['password'].value)
    xhr.open("POST", 'check/register.php', true)
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
    xhr.send('name=' + name + '&studentID=' + studentID + '&password=' + password)
}

function stopAllXHR() {
    for (let key in sending) {
        if (sending[key]) {
            XHR[key].abort()
            sending[key] = false
        }
    }
}
