let isEditingName = false;
let editarNombreButton;
let input;
let oldName;
let editarDescripcionButton;
let descripcionInput;
let cancelarDescripcionButton;
let descripcion;
let cambiarContrasenaButton;
let contrasenaActualInput;
let contrasenaActualError;

let contrasenaNuevaInput;
let contrasenaNuevaError;

let contrasenaConfirmacionInput;
let contrasenaConfirmacionError;

function onClickEditName(e) {
    
    input.toggleAttribute('readonly');
    if (isEditingName) {
        if (!input.checkValidity()) {
            return;
        }
        sendName(input.value);
        editarNombreButton.innerText = 'Editar';
    } else {
        oldName = input.value;
        editarNombreButton.innerText = 'Guardar';
    }
    isEditingName = !isEditingName;
}

function sendName(name) {
    editarNombreButton.classList.add('disabled');
    fetch('cambiarNombre.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: 'newName=' + name
    }).then((e) => {
        if (e.ok && e.status == 200) {

            e.json().then((value) => {
                input.value = value.name;
                editarNombreButton.classList.remove('disabled');
                showToast('Nombre cambiado exitosamente');
            })
        }
    })
}

function cambiarContrasena() {
    const fd = new FormData();
    
    fd.append('contrasenaActual', contrasenaActualInput.value);
    fd.append('contrasenaNueva', contrasenaNuevaInput.value);
    fd.append('contrasenaConfirmacion', contrasenaConfirmacionInput.value);

    fetch('api/cambiarContrasena.php', {
        method: 'POST',
        body: fd,
    }).then((res) => {
        if (res.ok) {
            res.json().then((data) => {
                const spinner = document.querySelector('span#contrasenaSpinner[role="status"].spinner-border');
                spinner.classList.add('d-none');
                switch (data.code) {
                    case 0:
                        cambiarContrasenaButton.removeAttribute('disabled');
                        contrasenaActualInput.value = '';
                        contrasenaNuevaInput.value = '';
                        contrasenaConfirmacionInput.value = '';
                        showToast(data.message);
                        document.querySelector('div.modal#cambiarContrasenaModal button.btn-close').click();
                        contrasenaActualInput.value = '';
                        contrasenaNuevaInput.value = '';
                        contrasenaConfirmacionInput.value = '';
                        break;
                    case 1:
                        contrasenaActualInput.value = '';
                        contrasenaActualInput.classList.add('is-invalid');
                        break;
                    case 2:
                        contrasenaNuevaInput.classList.add('is-invalid');
                        contrasenaConfirmacionInput.classList.add('is-invalid');
                        break;
                    default:
                        break;
                }
            })
        }
    })
}

function cambiarDescripcion() {

    // Get new description
    const newDescripcion = descripcionInput.value;

    const data = { descripcion: newDescripcion };

    document.querySelector('span.spinner-border').classList.remove('d-none');

    // Send req
    fetch('api/cambiarDescripcion.php',
    {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    }).then(
        (res) => {
            document.querySelector('span.spinner-border').classList.add('d-none');
            if (res.ok && res.status === 200) {
                res.json().then((data)=> {
                    if (data.code === 0) {
                        toggleDescripcionFields(true);
                        cancelarDescripcionButton.classList.add('d-none');
                        descripcionInput.value = data.descripcion
                        showToast(data.message);
                    }
                })
            } else if (res.status === 400) {
                toggleDescripcionFields(true);
                res.json().then(data => {
                    if (data.code === 1) {
                    }
                })
            } else {
                toggleDescripcionFields(true);
                showToast('Ocurrio un error al intentar conectar con el servidor', 'danger');
            }
        }
    ).catch(() => {
        toggleDescripcionFields(true);
        showToast('Ocurrio un error al intentar conectar con el servidor', 'danger');
    })

}

function onClickEditDescripcionButton() {
    if (editarDescripcionButton.isEditing) {
        descripcionInput.setAttribute('disabled', true);

        editarDescripcionButton.setAttribute('disabled', true);
        editarDescripcionButton.innerText = 'Editar';
        cancelarDescripcionButton.classList.add('d-none');
        // Disable fields
        toggleDescripcionFields(false);
        cambiarDescripcion();
    } else {
        descripcionInput.removeAttribute('disabled');
        cancelarDescripcionButton.classList.remove('d-none');

        editarDescripcionButton.innerText = 'Guardar';
    }
    editarDescripcionButton.isEditing = !editarDescripcionButton.isEditing;
    
}

function toggleDescripcionFields(enabled) {
    if (enabled) {
        descripcionInput.removeAttribute('disabled');
        editarDescripcionButton.removeAttribute('disabled');
        cancelarDescripcionButton.removeAttribute('disabled');
    } else {
        descripcionInput.setAttribute('disabled', true);
        editarDescripcionButton.setAttribute('disabled', true);
        cancelarDescripcionButton.setAttribute('disabled', true);
    }
}

window.onload = (e) => {
    editarNombreButton = document.querySelector('button#editarNombre');
    editarNombreButton.addEventListener('click', onClickEditName);

    input = document.querySelector('input#nombre[type="text"]');

    descripcionInput = document.querySelector('textarea#descripcion');

    cancelarDescripcionButton = document.querySelector('button#cancelarDescripcionButton');
    cancelarDescripcionButton.addEventListener('click', (e) => {
        editarDescripcionButton.innerText = 'Editar';
        editarDescripcionButton.isEditing = false;
        descripcionInput.value = descripcion;
        cancelarDescripcionButton.classList.add('d-none');
        descripcionInput.setAttribute('disabled', true);
    })

    editarDescripcionButton = document.querySelector('button#descripcionButton');

    editarDescripcionButton.isEditing = false;
    editarDescripcionButton.addEventListener('click', (e) => {
        onClickEditDescripcionButton();
    })

    descripcion = descripcionInput.value;

    // Setup para cambiar contrasena

    contrasenaActualInput = document.querySelector('input[type="password"]#contrasenaActualInput');
    contrasenaActualError = document.querySelector('div#contrasenaActualError');

    contrasenaNuevaInput = document.querySelector('input[type="password"]#contrasenaNuevaInput');
    contrasenaNuevaError = document.querySelector('div#contrasenaNuevaError');

    contrasenaConfirmacionInput = document.querySelector('input[type="password"]#contrasenaConfirmacionInput');
    contrasenaConfirmacionError = document.querySelector('div#contrasenaConfirmacionError');

    cambiarContrasenaButton = document.querySelector('button#cambiarContrasenaButton');
    cambiarContrasenaButton.addEventListener('click', (e) => {

        if (!(contrasenaActualInput.checkValidity() && contrasenaNuevaInput.checkValidity() && contrasenaConfirmacionInput.checkValidity && contrasenaConfirmacionInput.value === contrasenaNuevaInput.value)) {
            showToast('Por favor, corrija los errores', 'danger');
            return;
        }
        cambiarContrasenaButton.setAttribute('disabled', true);
        const spinner = document.querySelector('span#contrasenaSpinner[role="status"].spinner-border');
        spinner.classList.remove('d-none');
        cambiarContrasena();
    })

    contrasenaActualInput.addEventListener('input', (e) => {
        if (contrasenaActualInput.validity.valid) {
            contrasenaActualError.className = '';
            contrasenaActualError.textContent = '';
            contrasenaActualInput.classList.remove('is-invalid');
            contrasenaActualInput.classList.add('is-valid');
        } else {
            // Check error
            if (contrasenaActualInput.validity.valueMissing) {
                contrasenaActualError.textContent = 'Contrasena actual es requerida';
            } else if (contrasenaActualInput.validity.tooShort) {
                contrasenaActualError.textContent = `Minimo ${contrasenaActualInput.minLength} caracteres`;
            } else if (contrasenaActualInput.validity.tooLong) {
                contrasenaActualError.textContent = `Maximo ${contrasenaActualInput.maxLength} caracteres`;
            } else if (contrasenaActualInput.validity.patternMismatch) {
                contrasenaActualError.textContent = 'Debe contener minimo 1 caracter mayuscula, 1 numero y un caracter especial, sin espacios blancos';
            }

            contrasenaActualInput.classList.add('is-invalid');
            contrasenaActualError.className = 'invalid-feedback';
        }
    });

    contrasenaNuevaInput.addEventListener('input', (e) => {
        if (contrasenaNuevaInput.validity.valid) {
            contrasenaNuevaError.classList.remove('invalid-feedback');
            contrasenaNuevaError.textContent = '';
            contrasenaNuevaInput.classList.remove('is-invalid');
            contrasenaNuevaInput.classList.add('is-valid');
        } else {
            // Check error
            if (contrasenaNuevaInput.validity.valueMissing) {
                contrasenaNuevaError.textContent = 'Contrasena nueva es requerida';
            } else if (contrasenaNuevaInput.validity.tooShort) {
                contrasenaNuevaError.textContent = `Minimo ${contrasenaNuevaInput.minLength} caracteres`;
            } else if (contrasenaNuevaInput.validity.tooLong) {
                contrasenaNuevaError.textContent = `Maximo ${contrasenaNuevaInput.maxLength} caracteres`;
            } else if (contrasenaNuevaInput.validity.patternMismatch) {
                contrasenaNuevaError.textContent = 'Debe contener minimo 1 caracter mayuscula, 1 numero y un caracter especial, sin espacios blancos';
            }

            contrasenaNuevaInput.classList.add('is-invalid');
            contrasenaNuevaError.className = 'invalid-feedback';
        }

        if (contrasenaConfirmacionInput.value !== '') {
            contrasenaConfirmacionInput.classList.remove('is-invalid')
            contrasenaConfirmacionInput.classList.remove('is-valid')
        }
    })

    contrasenaConfirmacionInput.addEventListener('input', (e) => {
        if (contrasenaConfirmacionInput.validity.valid && contrasenaConfirmacionInput.value === contrasenaNuevaInput.value) {
            contrasenaConfirmacionError.className = '';
            contrasenaConfirmacionError.textContent = '';
            contrasenaConfirmacionInput.classList.remove('is-invalid');
            contrasenaConfirmacionInput.classList.add('is-valid');
        } else {
            // Check error
            if (contrasenaConfirmacionInput.validity.valueMissing) {
                contrasenaConfirmacionError.textContent = 'Se requiere confirmacion de contrasena';
            } else if (contrasenaConfirmacionInput.value !== contrasenaNuevaInput.value) {
                contrasenaConfirmacionError.textContent = 'Las contrasena no coinciden';
            }

            contrasenaConfirmacionInput.classList.add('is-invalid');
            contrasenaConfirmacionError.className = 'invalid-feedback';
        }
    })
}