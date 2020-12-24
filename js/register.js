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
            return
        }
        let value = doc_text.value
        doc_text.className = 'input-check-process'
        doc_text.setCustomValidity('检查是否可用中...')
        let xhr;
        if (XHR[type]) {
            xhr = XHR[type]
        } else {
            xhr = new XMLHttpRequest();
            if (!xhr) {
                alert('浏览器不支持xhr！')
                return
            }
            XHR[type] = xhr
            xhr.onload = function () {
                if (xhr.status === 200) {
                    //success
                    doc_text.className = ''
                    doc_text.setCustomValidity('')
                } else if (xhr.status === 422) {
                    //error
                    doc_text.className = 'input-check-error'
                    sp.className = 'span-check-error'
                    doc_text.setCustomValidity(xhr.responseText)
                    //alert(xhr.responseText)
                } else {
                    //fail
                    //alert('服务器连接失败！')
                }
                sending[type] = false
            }
            xhr.timeout = 2000;
            xhr.ontimeout = function () {
                alert('请求服务器超时！')
                sending[type] = false
            }
        }
        xhr.open("POST", 'register_check/' + type + '.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send('value=' + value);
        sending[type] = true
    }, 500)
}

function regResetAll() {
    for (let key in reg_sps) {
        reg_inputs[key].className = ''
        reg_inputs[key].setCustomValidity('')
        reg_sps[key].className = ''
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
            alert('浏览器不支持xhr！')
            return
        }
        xhr.onload = function () {
            if (xhr.status === 200) {
                //success
                alert('注册成功！跳到登录界面！')
                reg_form.reset()
                document.querySelector('.content').classList.toggle('s--signup')
            } else if (xhr.status === 422) {
                //error
                alert(xhr.responseText)
            } else {
                //fail
                alert('未知错误！')
            }
            btn_reg.innerText = '注 册'
            btn_reg.disabled = false
            btn_rst.disabled = false
        }
        xhr.timeout = 2000;
        xhr.ontimeout = function () {
            alert('请求服务器超时！')
            btn_reg.innerText = '注 册'
            btn_reg.disabled = false
            btn_rst.disabled = false
        }
    }
    let name = reg_inputs['name'].value
    let studentID = reg_inputs['studentID'].value
    let password = hex_md5(reg_inputs['password'].value)
    xhr.open("POST", 'register_check.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send('name=' + name + '&studentID=' + studentID + '&password=' + password);
}
