document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("form");

    form.addEventListener("submit", async (e) => {
          e.preventDefault();  
        const name = document.getElementById("name").value.trim();
        const email = document.getElementById("email").value.trim();
        const message = document.getElementById("message").value.trim();
        if (!name || !email || !message) {
            Swal.fire({
                icon: "warning",
                iconColor: '#d1a57bff',
                title: "Complete todos los campos",
                color: 'white',
                confirm: 'none',
                background: '#1b2033',
                showConfirmButton: false,
                customClass: {
                    title: 'alert_title',
                    content: 'text-center',
                },
            });
            return;
        }

        if (name && email && message) {
            const data = {
                name: name,
                email: email,
                message: message
            };

            try {
                const response = await fetch("php/contacto.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(data)
                });
                const result = await response.text();
                if (response.ok) {
                    Swal.fire({
                        icon: "success",
                        iconColor: '#8AD17B',
                        title: "¡Formulario enviado!",
                        color: 'white',
                        confirm: 'none',
                        background: '#1b2033',
                        showConfirmButton: false,
                        customClass: {
                            title: 'alert_title',
                            content: 'text-center',
                        },

                    });
                    setTimeout(() => {
                               window.location.href = '/index.html';
                    }, 300)
        
                } else {
                    alert("Error: " + result);
                }

            } catch (error) {
                alert("Connection error: " + error);
            }
        }
    });
});