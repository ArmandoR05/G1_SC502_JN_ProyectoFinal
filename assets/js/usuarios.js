document.addEventListener("DOMContentLoaded", () => {
    cargarUsuarios();
    cargarRoles();
});

async function cargarUsuarios() {
    try {
        const response = await fetch('php/usuarios/listar_usuarios.php');
        const text = await response.text();
        
        const parser = new DOMParser();
        const doc = parser.parseFromString(text, 'text/html');
        const tabla = doc.querySelector('tbody');
        
        if (tabla) {
            document.getElementById('tablaUsuariosBody').innerHTML = tabla.innerHTML;
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function cargarRoles() {
    try {
        const response = await fetch('php/usuarios/obtener_roles.php');
        const roles = await response.json();
        
        const select = document.getElementById('id_rol');
        select.innerHTML = roles.map(rol => 
            `<option value="${rol.id_rol}">${rol.nombre_rol}</option>`
        ).join('');
    } catch (error) {
        console.error('Error:', error);
    }
}

function nuevoUsuario() {
    document.getElementById('formUsuario').reset();
    document.getElementById('id_usuario').value = '';
    document.getElementById('accion').value = 'crear';
    document.getElementById('tituloModal').textContent = 'Nuevo Usuario';
    document.getElementById('grupoPassword').style.display = 'block';
}

function editarUsuario(id, nombre, apellido, email, telefono, cedula, id_rol, estado) {
    document.getElementById('id_usuario').value = id;
    document.getElementById('nombre').value = nombre;
    document.getElementById('apellido').value = apellido;
    document.getElementById('email').value = email;
    document.getElementById('telefono').value = telefono || '';
    document.getElementById('cedula').value = cedula || '';
    document.getElementById('id_rol').value = id_rol;
    document.getElementById('estado').value = estado;
    document.getElementById('accion').value = 'editar';
    document.getElementById('tituloModal').textContent = 'Editar Usuario';
    document.getElementById('grupoPassword').style.display = 'none';
    
    const modal = new bootstrap.Modal(document.getElementById('modalUsuario'));
    modal.show();
}

async function guardarUsuario() {
    const formData = new FormData(document.getElementById('formUsuario'));
    const accion = formData.get('accion');
    
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
    
    try {
        const url = accion === 'crear' ? 'php/usuarios/crear_usuario.php' : 'php/usuarios/editar_usuario.php';
        const response = await fetch(url, {
            method: 'POST',
            body: formData
        });
        
        const result = await response.text();
        
        if (result.includes('ok')) {
            Toast.fire({
                icon: 'success',
                title: 'Usuario guardado exitosamente'
            });
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalUsuario'));
            modal.hide();
            
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            Toast.fire({
                icon: 'error',
                title: result.replace('error:', '').trim()
            });
        }
    } catch (error) {
        Toast.fire({
            icon: 'error',
            title: 'Error al guardar usuario'
        });
    }
}