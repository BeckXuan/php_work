let reg_form = document.getElementsByName('register')[0];
let reg_name = reg_form['name']
let reg_studentID = reg_form['studentID']
let timeId = {
    'name': null,
    'studentID': null
}
let XHR = {
    'name': null,
    'studentID': null
}
let sending = {
    'name': false,
    'studentID': false
}

function isEmpty(text) {
    return text === undefined || text === "" || text === null
}

function validate(type, doc_text) {
    if (timeId[type]) {
        clearTimeout(timeId[type])
        timeId[type] = null
    }
    if (sending[type]) {
        XHR[type].abort()
        sending[type] = false
    }
    doc_text.className = ''
    timeId[type] = setTimeout(() => {
        timeId[type] = null
        let value = doc_text.value
        if (isEmpty(value)) {
            return
        }
        doc_text.className = 'check-process'
        let xhr;
        if (XHR[type]) {
            xhr = XHR[type]
        } else {
            if (window.XMLHttpRequest) {// IE7+, Firefox, Chrome, Opera, Safari 代码
                xhr = new XMLHttpRequest();
            } else {// IE6, IE5 代码
                xhr = new ActiveXObject("Microsoft.XMLHTTP");
            }
            XHR[type] = xhr
            xhr.onload = function () {
                sending[type] = false
                if (xhr.status === 200) {
                    //success
                    doc_text.className = ''
                } else if (xhr.status === 422) {
                    //error
                    doc_text.className = 'check-error'
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
    reg_name.className = ''
    reg_studentID.className = ''
}

function _register() {
    return false;
}
