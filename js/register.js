let reg_form = document.getElementsByName('register')[0];
let reg_name = reg_form['name']
let reg_studentID = reg_form['studentID']
let last_text = {
    'name' : '',
    'studentID': ''
}

function isEmpty(text) {
    return text === undefined || text === "" || text === null
}

reg_name.addEventListener("blur", () => {
    validate('name', reg_name)
}, false)
reg_studentID.addEventListener("blur", () => {
    validate('studentID', reg_studentID)
}, false)

function validate(type, doc_text) {
    let value = doc_text.value
    if (last_text[type] === value) {
        return;
    }
    if (isEmpty(value)) {
        reg_name.className = ''
        return
    }
    last_text[type] = value;
    let xmlHttp;
    if (window.XMLHttpRequest) {// IE7+, Firefox, Chrome, Opera, Safari 代码
        xmlHttp = new XMLHttpRequest();
    } else {// IE6, IE5 代码
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState === 4) {
            if (xmlHttp.status === 200) {
                //success
                doc_text.className = ''
            } else if (xmlHttp.status === 422) {
                //error
                doc_text.className = 'check-error'
                alert(xmlHttp.responseText)
            } else {
                //fail
                alert('服务器连接失败！')
            }
        }
    }
    xmlHttp.open("POST", 'register_check/' + type + '.php', true);
    xmlHttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xmlHttp.send('value=' + value);
}

function _register() {
    return false;
}
