<?php view('header') ?>

<?php view('navbar') ?>

<?php [$inputs, $errors] = session_flash('inputs', 'errors'); ?>
<!-- Seccion principal -->
<section class="iniciar-sesion container-fluid py-4">
    <div class="row py-4 my-4 justify-content-center">
        <div class="col-7 d-flex flex-column">
            <div class="card p-5">
                <div class="p-5 d-flex flex-column">
                    <small class="vendor pb-1 fw-bold">Visor3D</small>
                    <h1 class="iniciar-sesion__header-text text-center fw-bold pb-2">Registrarse</h1>
                    <div class="card-body">
                        <form id="registrarForm" class="d-flex flex-column" action="signup.php" method="post">
                            <input class="form-control" type="email" name="email" id="email"
                                placeholder="Correo electronico" value="<?= $inputs['email'] ?? '' ?>" required
                                minlength="6" maxlength="320" autocomplete="email">
                            <div id="emailError"
                                class="pb-4 d-block <?=  (isset($errors['email']))? 'invalid-feedback' : '' ?>">
                                <?= $errors['email'] ?? '' ?></div>

                            <input class="form-control" type="text" name="username" id="username"
                                placeholder="Nombre de usuario" value="<?= $inputs['username'] ?? '' ?>" required
                                minlength="3" maxlength="25" pattern="^[a-zA-Z0-9]*$" autocomplete="nickname">
                            <div id="usernameError"
                                class="pb-4 d-block <?=  (isset($errors['username']))? 'invalid-feedback' : '' ?>">
                                <?= $errors['username'] ?? '' ?></div>

                            <input class="form-control" type="password" name="contrasena" id="contrasena"
                                placeholder="Contrasena" value="<?= $inputs['contrasena'] ?? '' ?>" required
                                minlength="8" maxlength="64"
                                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$=!%*?&])[A-Za-z\d@$=!%*?&]{8,64}$"
                                autocomplete="new-password">
                            <div id="contrasenaError"
                                class="pb-4 d-block <?=  (isset($errors['contrasena']))? 'invalid-feedback' : '' ?>">
                                <?= $errors['contrasena'] ?? '' ?></div>

                            <input class="form-control" type="password" name="contrasena2" id="contrasena2"
                                placeholder="Confirma contrasena" value="<?= $inputs['contrasena2'] ?? '' ?>" required
                                minlength="8" maxlength="64" autocomplete="new-password">
                            <div id="contrasena2Error"
                                class="pb-4 d-block <?=  (isset($errors['contrasena2']))? 'invalid-feedback' : '' ?>">
                                <?= $errors['contrasena2'] ?? '' ?></div>

                            <button class="btn btn-success rounded-pill" type="submit">Registrar</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="d-flex flex-column align-items-center">


    </div>
</section>

<!-- <section class="seccion-registrar">
    <div class="d-flex flex-column align-items-center">
        <h1 class="header-text">Registrar cuenta</h1>
        <form id="registrarForm" class="d-flex flex-column" action="signup.php" method="post">
            <input class="form-control" type="email" name="email" id="email" placeholder="Correo electronico"
                value="<?= $inputs['email'] ?? '' ?>" required minlength="6" maxlength="320" autocomplete="email">
            <div id="emailError" class="d-block <?=  (isset($errors['email']))? 'invalid-feedback' : '' ?>">
                <?= $errors['email'] ?? '' ?></div>

            <input class="form-control" type="text" name="username" id="username" placeholder="Nombre de usuario"
                value="<?= $inputs['username'] ?? '' ?>" required minlength="3" maxlength="25" pattern="^[a-zA-Z0-9]*$"
                autocomplete="nickname">
            <div id="usernameError" class="d-block <?=  (isset($errors['username']))? 'invalid-feedback' : '' ?>">
                <?= $errors['username'] ?? '' ?></div>

            <input class="form-control" type="password" name="contrasena" id="contrasena" placeholder="Contrasena"
                value="<?= $inputs['contrasena'] ?? '' ?>" required minlength="8" maxlength="64"
                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$=!%*?&])[A-Za-z\d@$=!%*?&]{8,64}$"
                autocomplete="new-password">
            <div id="contrasenaError" class="d-block <?=  (isset($errors['contrasena']))? 'invalid-feedback' : '' ?>">
                <?= $errors['contrasena'] ?? '' ?></div>

            <input class="form-control" type="password" name="contrasena2" id="contrasena2"
                placeholder="Confirma contrasena" value="<?= $inputs['contrasena2'] ?? '' ?>" required minlength="8"
                maxlength="64" autocomplete="new-password">
            <div id="contrasena2Error" class="d-block <?=  (isset($errors['contrasena2']))? 'invalid-feedback' : '' ?>">
                <?= $errors['contrasena2'] ?? '' ?></div>

            <button class="btn btn-success" type="submit">Registrar</button>
        </form>
    </div>
</section> -->

<script src="js/validacion.js"></script>

<script>
window.addEventListener('load', (e) => {
    const emailError = document.querySelector('div#emailError');
    document.querySelector('input#email').addEventListener('input', validateListener(emailError));

    const usernameError = document.querySelector('div#usernameError');
    document.querySelector('input#username').addEventListener('input', validateListener(usernameError, {
        patternMismatch: 'Debe contener solo numeros y letras'
    }));

    const contrasenaError = document.querySelector('div#contrasenaError');
    document.querySelector('input#contrasena').addEventListener('input', validateListener(contrasenaError, {
        patternMismatch: 'Debe contener minimo 1 caracter mayuscula, 1 numero y un caracter especial como @$=!%*?&'
    }));

    const contrasena2Error = document.querySelector('div#contrasena2Error');
    const input = document.querySelector('input#contrasena');
    const mirror = document.querySelector('input#contrasena2');
    document.addEventListener('keyup', sameAsListener(input, mirror, contrasena2Error));

    document.querySelector('form#registrarForm').addEventListener('submit', (e) => {
        if (input.value !== mirror.value) {
            e.preventDefault();
        }
    })
})
</script>

<?php view('footer') ?>