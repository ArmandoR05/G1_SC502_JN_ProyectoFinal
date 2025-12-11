document.getElementById("registroForm").addEventListener("submit", async function (e) {

    e.preventDefault();

    const nombre = document.getElementById("nombre").value.trim();
    const apellido = document.getElementById("apellido").value.trim();
    const email = document.getElementById("email").value.trim();
    const telefono = document.getElementById("telefono").value.trim();
    const cedula = document.getElementById("cedula").value.trim();
    const password = document.getElementById("password").value.trim();
    const confirmar = document.getElementById("confirmar").value.trim();

    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: "#fff",
        color: "#000",
        didOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer)
            toast.addEventListener("mouseleave", Swal.resumeTimer)
        }
    });

    if (!nombre || !apellido || !email || !password || !confirmar) {
        Toast.fire({
            icon: "warning",
            title: "Debe completar todos los campos obligatorios."
        })
        return;
    }

    if(password !== confirmar){
        Toast.fire({
            icon: "error",
            title: "Las contraseñas no coinciden."
        })
        return;
    }

    if(password.length < 6){
        Toast.fire({
            icon: "warning",
            title: "La contraseña debe tener al menos 6 caracteres."
        })
        return;
    }

    const datos = new FormData();

    datos.append("nombre", nombre);
    datos.append("apellido", apellido);
    datos.append("email", email);
    datos.append("telefono", telefono);
    datos.append("cedula", cedula);
    datos.append("password", password);

    try{

        const response = await fetch("php/registro/registro.php", {
            method: "POST",
            body: datos
        })

        const result = await response.text();

        if(result.includes("ok")){
            Toast.fire({
                icon: "success",
                title: "Usuario registrado con éxito."
            })

            setTimeout(() => {
                window.location.href = "index.php"
            }, 2000)

        }else if(result.includes("error:")){
            Toast.fire({
                icon: "error",
                title: result.replace("error:", "").trim()
            })
        }else{
            Toast.fire({
                icon: "error",
                title: "Ocurrió un error inesperado al registrar al usuario."
            })
        }

    }catch(error){

        console.log(error)

        Toast.fire({
            icon: "error",
            title: "Error de conexión con el servidor. " + error
        })
    }

})