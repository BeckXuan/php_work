let log_form = document.getElementById('login')
let input_studentID = log_form.studentID
let input_password = log_form.password
let input_rem = log_form['rem']
let btn_log = log_form['btn_log']
let btn_admin = log_form['btn_admin']
let xhr = null
log_form.addEventListener('submit', _login)
btn_admin.addEventListener('click', () => {
    window.location.href = 'admin/login.php'
})

function _login(e) {
    e.preventDefault()
    btn_log.disabled = true
    btn_log.innerText = '登 录 中...'
    let studentID = input_studentID.value
    let password = hex_md5(input_password.value)
    let rem = input_rem.checked ? 1 : 0
    if (!xhr) {
        if (window.XMLHttpRequest) {
            xhr = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            xhr = new ActiveXObject("Microsoft.XMLHTTP");
        } else {
            layer.msg('浏览器不支持XMLHttpRequest！', {icon: 2, time: 3000})
            return
        }
        xhr.onload = function () {
            let _status = xhr.status
            let _message = xhr.responseText
            if (_status === 200) {
                //success
                layer.msg('登录成功！跳转中...', {icon: 1, time: 0})
                setTimeout(() => {
                    window.location.href = 'index.php'
                }, 800)
                return
            }
            if (_status !== 406) {
                _message = _status + '未知错误！'
            }
            layer.msg(_message, {icon: 2, time: 3000})
            btn_log.innerText = '登 录'
            btn_log.disabled = false
        }
        xhr.timeout = 2000;
        xhr.ontimeout = function () {
            layer.msg('请求服务器超时！', {icon: 2, time: 3000})
            btn_log.innerText = '登 录'
            btn_log.disabled = false
        }
    }
    xhr.open("POST", 'check/login.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send('studentID=' + studentID + '&password=' + password + '&rem=' + rem);
}