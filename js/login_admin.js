let log_form = document.getElementById('login')
let input_account = log_form['account']
let input_password = log_form['password']
let input_rem = log_form['rem']
let btn_log = log_form['btn_log']
let btn_usr = log_form['btn_usr']
let XHR = null
log_form.addEventListener('submit', _login)
btn_usr.addEventListener('click', () => {
    window.location.href = 'login.php'
})

function _login(e) {
    e.preventDefault()
    btn_log.disabled = true
    btn_log.innerText = '登 录 中...'
    let account = input_account.value
    let password = hex_md5(input_password.value)
    let rem = input_rem.checked ? 1 : 0
    let xhr
    if (XHR) {
        xhr = XHR
    } else {
        xhr = new XMLHttpRequest();
        if (!xhr) {
            alert('浏览器不支持xhr！')
            return
        }
        XHR = xhr
        xhr.onload = function () {
            if (xhr.status === 200) {
                //success
                alert('登陆成功！')
                window.location.href = 'index_admin.php'
            } else if (xhr.status === 422) {
                //error
                alert(xhr.responseText)
            } else {
                //fail
                alert('未知错误！')
            }
            btn_log.innerText = '登 录'
            btn_log.disabled = false
        }
        xhr.timeout = 2000;
        xhr.ontimeout = function () {
            alert('请求服务器超时！')
            btn_log.innerText = '登 录'
            btn_log.disabled = false
        }
    }
    xhr.open("POST", 'admin_check.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send('account=' + account + '&password=' + password + '&rem=' + rem);
}