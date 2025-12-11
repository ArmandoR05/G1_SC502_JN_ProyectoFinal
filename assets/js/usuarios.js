let usuarioEditando = null;

document.addEventListener("DOMContentLoaded", () => {
    cargarUsuarios();
    cargarRoles();
});

// Cargar lista de usuarios
async function cargarUsuarios() {
    try {
        const response = await fetch('php/usuarios/listar_usuarios.php');
        const data = await response.json();

        if (data.status === 'ok') {
            mostrarUsuarios(data.datos);
        } else {
            mostrarAlerta('error', data.mensaje);
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('error', 'Error al cargar usuarios');
    }
}

// Mostrar usuarios en la tabla
function mostrarUsuarios(usuarios) {
    const tbody = document.getElementById("tablaUsuarios");
    
    if (usuarios.length === 0) {
        tbody.innerHTML = `<tr><td colspan="8" class="text-center">No hay usuarios registrados</td></tr>`;
        return;
    }

    tbody.innerHTML = usuarios.map(usuario => `
        <tr>
            <td>${usuario.id_usuario}</td>
            <td>${usuario.nombre} ${usuario.apellido}</td>
            <td>${usuario.email}</td>
            <td>${usuario.telefono || '-'}</td>
            <td>${usuario.cedula || '-'}</td>
            <td><span class="badge bg-info">${usuario.nombre_rol}</span></td>
            <td>
                <span class="badge ${usuario.estado === 'activo' ? 'bg-success' : 'bg-danger'}">
                    ${usuario.estado}
                </span>
            </td>
            <td>
                <button class="btn btn-sm btn-warning" onclick="editarUsuario(${usuario.id_usuario})">
                    <i class="bi bi-pencil"></i>
                </button>
                <a href="php/usuarios/eliminar_usuario.php?id=${usuario.id_usuario}" 
                   class="btn btn-sm btn-danger" 
                   onclick="return confirm('¿Eliminar este usuario?')">
                    <i class="bi bi-trash"></i>
                </a>
            </td>
        </tr>
    `).join('');
}

// Cargar roles
async function cargarRoles() {
    try {
        const response = await fetch('php/usuarios/obtener_roles.php');
        const data = await response.json();

        if (data.status === 'ok') {
            const selectRol = document.getElementById("id_rol");
            selectRol.innerHTML = data.datos.map(rol => 
                `<option value="${rol.id_rol}">${rol.nombre_rol}</option>`
            ).join('');
        }
    } catch (error) {
        console.error('Error al cargar roles:', error);
    }
}

// Nuevo usuario
function nuevoUsuario() {
    usuarioEditando = null;
    document.getElementById("formUsuario").reset();
    document.getElementById("tituloModal").textContent = "Nuevo Usuario";
    document.getElementById("grupoPassword").style.display = "block";
    document.getElementById("id_usuario").value = "";
}

// Editar usuario
async function editarUsuario(id) {
    try {
        const response = await fetch('php/usuarios/listar_usuarios.php');
        const data = await response.json();

        if (data.status === 'ok') {
            const usuario = data.datos.find(u => u.id_usuario == id);
            
            if (usuario) {
                usuarioEditando = usuario;
                
                document.getElementById("id_usuario").value = usuario.id_usuario;
                document.getElementById("nombre").value = usuario.nombre;
                document.getElementById("apellido").value = usuario.apellido;
                document.getElementById("email").value = usuario.email;
                document.getElementById("telefono").value = usuario.telefono || '';
                document.getElementById("cedula").value = usuario.cedula || '';
                document.getElementById("id_rol").value = usuario.id_rol;
                document.getElementById("estado").value = usuario.estado;

                document.getElementById("grupoPassword").style.display = "none";
                document.getElementById("tituloModal").textContent = "Editar Usuario";

                const modal = new bootstrap.Modal(document.getElementById("modalUsuario"));
                modal.show();
            }
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('error', 'Error al cargar usuario');
    }
}

// Guardar usuario
async function guardarUsuario() {
    const id_usuario = document.getElementById("id_usuario").value;
    const nombre = document.getElementById("nombre").value.trim();
    const apellido = document.getElementById("apellido").value.trim();
    const email = document.getElementById("email").value.trim();
    const telefono = document.getElementById("telefono").value.trim();
    const cedula = document.getElementById("cedula").value.trim();
    const id_rol = document.getElementById("id_rol").value;
    const estado = document.getElementById("estado").value;
    const password = document.getElementById("password").value.trim();

    if (!nombre || !apellido || !email) {
        mostrarAlerta('warning', 'Complete los campos obligatorios');
        return;
    }

    if (!id_usuario && !password) {
        mostrarAlerta('warning', 'La contraseña es obligatoria');
        return;
    }

    try {
        const formData = new FormData();
        formData.append('nombre', nombre);
        formData.append('apellido', apellido);
        formData.append('email', email);
        formData.append('telefono', telefono);
        formData.append('cedula', cedula);
        formData.append('id_rol', id_rol);
        formData.append('estado', estado);

        let url = 'php/usuarios/crear_usuario.php';
        
        if (id_usuario) {
            url = 'php/usuarios/editar_usuario.php';
            formData.append('id_usuario', id_usuario);
        } else {
            formData.append('password', password);
        }

        const response = await fetch(url, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.status === 'ok') {
            mostrarAlerta('success', data.mensaje);
            
            const modal = bootstrap.Modal.getInstance(document.getElementById("modalUsuario"));
            modal.hide();

            cargarUsuarios();
        } else {
            mostrarAlerta('error', data.mensaje);
        }

    } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('error', 'Error al guardar usuario');
    }
}

// Mostrar alertas
function mostrarAlerta(tipo, mensaje) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });

    Toast.fire({
        icon: tipo,
        title: mensaje
    });
}