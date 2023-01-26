function validateListener(error, messages = {}) {
    return function validate(e) {
        const input = e.target;
        if (input.validity.valid) {
            error.classList.remove('invalid-feedback');
            error.textContent = '';
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        } else {
            // Check error
            input.classList.add('is-invalid');
            error.classList.add('invalid-feedback');
            for (const errorType in input.validity) {
                if (!input.validity[errorType]) continue;
                let message;
                if (messages[errorType]) {
                    message = messages[errorType];
                    error.textContent = message;
                    break;
                }
                switch (errorType) {
                    case 'badInput':
                        message = 'La informacion ingresada no es valida';
                        break;
                    case 'customError':
                        message = 'Invalido';
                        break;
                    case 'patternMismatch':
                        message = 'La informacion ingresada no es valida';
                        break;
                    case 'rangeOverflow':
                        message = 'El valor excede el rango permitido';
                        break;
                    case 'rangeUnderflow':
                        message = 'El valor es menor al rango permitido';
                        break;
                    case 'stepMismatch':
                        message = 'El valor es invalido';
                        break;
                    case 'tooLong':
                        message = `Maximo ${input.maxLength} caracteres`;
                        break;
                    case 'tooShort':
                        message = `Minimo ${input.minLength} caracteres`;
                        break;
                    case 'typeMismatch':
                        message = `Formato incorrecto. Debe ser ${input.type}.`;
                        break;
                    case 'valueMissing':
                        message = 'El campo es requerido';
                        break;
                    default:
                        message = 'Invalido';
                        break;
                }
                error.textContent = message;
                break;
            }
        }
    }
}

function sameAsListener (input, mirror, error) {
    return function(e) {
        if (input.value === '' && mirror.value === '') {
            error.classList.remove('invalid-feedback');
            error.textContent = '';
            mirror.classList.remove('is-invalid');
            mirror.classList.remove('is-valid');
        } else if (input.value === mirror.value) {
            error.classList.remove('invalid-feedback');
            error.textContent = '';
            mirror.classList.remove('is-invalid');
            mirror.classList.add('is-valid');
        } else {
            mirror.classList.add('is-invalid');
            error.classList.add('invalid-feedback');
            error.textContent = 'Las contrasenas no coinciden';
        }
    }
}