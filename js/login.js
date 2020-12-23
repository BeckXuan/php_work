let log_form = document.getElementById('login')
let input_studentID = log_form.studentID
let input_password = log_form.password
let input_rem = log_form['rem']
let button = log_form['btn_log']
let XHR_log = null
log_form.addEventListener('submit', _login)

function _login(e) {
    e.preventDefault()
    button.disabled = true
    button.innerText = '登 录 中...'
    let studentID = input_studentID.value
    let password = hex_md5(input_password.value)
    let rem = input_rem.checked ? 1 : 0
    let xhr
    if (XHR_log) {
        xhr = XHR_log
    } else {
        xhr = new XMLHttpRequest();
        if (!xhr) {
            alert('浏览器不支持xhr！')
            return
        }
        XHR_log = xhr
        xhr.onload = function () {
            if (xhr.status === 200) {
                //success
                alert('登陆成功！')
                window.location.href = 'content.php'
            } else if (xhr.status === 422) {
                //error
                alert(xhr.responseText)
            } else {
                //fail
                alert('未知错误！')
            }
            button.innerText = '登 录'
            button.disabled = false
        }
        xhr.timeout = 2000;
        xhr.ontimeout = function () {
            alert('请求服务器超时！')
            button.innerText = '登 录'
            button.disabled = false
        }
    }
    xhr.open("POST", 'login_check.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send('studentID=' + studentID + '&password=' + password + '&rem=' + rem);
}