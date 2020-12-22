let log_form = document.getElementById('login')
let input_studentID = log_form.studentID
let input_password = log_form.password
let input_rem = log_form.rem
let XMR = null
log_form.addEventListener('submit', _login)

function _login(e) {
    e.preventDefault()
    let studentID = input_studentID.value
    let password = hex_md5(input_password.value)
    let rem = input_rem.checked ? 1 : 0
    let xhr
    if (!XHR) {
        xhr = XHR
    } else {
        xhr = new XMLHttpRequest();
        if (!xhr) {
            alert('浏览器不支持xhr！')
            return
        }
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
            xhr.timeout = 2000;
            xhr.ontimeout = function () {
                alert('请求服务器超时！')
            }
        }
    }
    xhr.open("POST", 'login_check.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send('studentID=' + studentID + '&password=' + password + '&rem=' + rem);
}