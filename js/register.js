let reg_form = document.getElementById('register')
let eye = document.getElementById("passwordEye")
let reg_inputs = {
    'name': reg_form.name,
    'studentID': reg_form.studentID,
    'password': reg_form.password,
    'button': reg_form['btn_reg']
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

function regResetAll(e) {
    if (reg_inputs['button'].disabled){
        e.preventDefault()
        return
    }
    for (let key in reg_sps) {
        reg_inputs[key].className = ''
        reg_inputs[key].setCustomValidity('')
        reg_sps[key].className = ''
    }
}

function changeVisibility() {
    eye.classList.toggle("visible")
    let input_password = reg_inputs['password']
    if (input_password.type === 'password') {
        input_password.type = 'text'
    } else {
        input_password.type = 'password'
    }
}

function _register(e) {
    e.preventDefault()
    let button = reg_inputs['button']
    button.disabled = true
    button.innerText = '注 册 中...'
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
                alert('注册成功！')
            } else if (xhr.status === 422) {
                //error
                alert(xhr.responseText)
            } else {
                //fail
                alert('未知错误！')
            }
            button.innerText = '注 册'
            button.disabled = false
        }
        xhr.timeout = 2000;
        xhr.ontimeout = function () {
            alert('请求服务器超时！')
            button.innerText = '注 册'
            button.disabled = false
        }
    }
    let name = reg_inputs['name'].value
    let studentID = reg_inputs['studentID'].value
    let password = hex_md5(reg_inputs['password'].value)
    xhr.open("POST", 'register_check.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send('name=' + name + '&studentID=' + studentID + '&password=' + password);
}
