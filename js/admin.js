let btn_logout = document.getElementById('btn_logout')

btn_logout.addEventListener('click', logout)

function logout() {
    let con = confirm('确认要退出吗？')
    if (con) {
        window.location.href = '../logout.php'
    }
}
