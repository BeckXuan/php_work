let reg_form = document.getElementsByName('register')[0]
let eye = document.getElementById("passwordEye")
let reg_inputs = {
    'name': reg_form['name'],
    'studentID': reg_form['studentID'],
    'password': reg_form['password']
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
    'studentID': false
}

for (let key in reg_sps) {
    reg_inputs[key].addEventListener('input', () => {
        validate(key)
    }, false)
}
eye.addEventListener('click', changeVisibility)

function isEmpty(text) {
    return text === undefined || text === "" || text === null
}

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
    timeId[type] = setTimeout(() => {
        timeId[type] = null
        let value = doc_text.value
        sp.className = ''
        if (isEmpty(value)) {
            doc_text.className = ''
            return
        }
        doc_text.className = 'input-check-process'
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
                sending[type] = false
                if (xhr.status === 200) {
                    //success
                    doc_text.className = ''
                } else if (xhr.status === 422) {
                    //error
                    doc_text.className = 'input-check-error'
                    sp.className = 'span-check-error'
                    //alert(xhr.responseText)
                } else {
                    //fail
                    //alert('服务器连接失败！')
                }
            }
            xhr.timeout = 2000;
            xhr.ontimeout = function () {
                alert('请求服务器超时！')
            }
        }
        xhr.open("POST", 'register_check/' + type + '.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send('value=' + value);
        sending[type] = true
    }, 500)
}

function resetAll() {
    for (let key in reg_inputs) {
        reg_inputs[key].className = ''
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

function _register() {
    let xhr
    if (XHR['submit']) {
        xhr = XHR['submit']
    } else {
        xhr = new XMLHttpRequest();
        if (!xhr) {
            alert('浏览器不支持xhr！')
            return
        }
    }
    xhr.open("POST", 'register_check/.php', true);
    return false;
}
