document.getElementById("loginForm").addEventListener('submit', async function (e) {

    e.preventDefault();

    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();

    if (email.length == 0) {
        Swal.fire({
            icon: 'error',
            title: 'Datos faltantes',
            text: 'Debe ingresar un email válido.',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true
        })
        return;
    }

    if (!password) {
        Swal.fire({
            icon: 'error',
            title: 'Datos faltantes',
            text: 'Debe ingresar una contraseña válida.',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true
        })
        return;
    }

    try {
        const respuesta = await fetch('php/login/login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password })
        })

        const texto = await respuesta.text()
        let data;

        data = JSON.parse(texto)

        if (data.status === 'ok') {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: 'Inicio de sesión exitoso. Bienvenido: ' + data.nombre,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true
            })

            setTimeout(() => {
                window.location.href = "home.php"
            }, 2000)
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.mensaje,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true
            })
        }

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se logró contactar al servidor. Error: ' + error,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true
        })
    }

})