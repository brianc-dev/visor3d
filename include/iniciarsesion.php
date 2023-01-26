<?php view('header') ?>

<?php view('navbar') ?>

<?php [$inputs, $errors] = session_flash('inputs', 'errors'); ?>
<!-- Seccion principal -->
<section class="iniciar-sesion container-fluid py-4">
    <div class="row py-4 my-4 justify-content-center">
        <div class="col-auto d-flex flex-column">
            <div class="card p-5">
                <div class="p-5 d-flex flex-column">
                    <small class="vendor pb-1 fw-bold">Visor3D</small>
                    <h1 class="iniciar-sesion__header-text text-center fw-bold pb-2">Iniciar sesion</h1>
                    <div class="card-body">
                        <form class="d-flex flex-column" action="login.php" method="post">

                            <input class="form-control" type="text" name="username" id="username" placeholder="Usuario"
                                value="<?= $inputs['username'] ?? '' ?>" required minlength="3" maxlength="25"
                                pattern="^[a-zA-Z0-9]*$" autocomplete="on">
                            <div id="usernameError"
                                class="pb-4 d-block <?=  (isset($errors['username']))? 'invalid-feedback' : '' ?>">
                                <?= $errors['username'] ?? '' ?>
                            </div>

                            <input class="form-control" type="password" name="contrasena" id="contrasena"
                                placeholder="Contrasena" required autocomplete="current-password">
                            <div id="contrasenaError"
                                class="pb-4 d-block <?=  (isset($errors['contrasena']))? 'invalid-feedback' : '' ?>">
                                <?= $errors['contrasena'] ?? '' ?>
                            </div>
                            <button class="btn btn-success rounded-pill" type="submit">Iniciar sesion</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="d-flex flex-column align-items-center">


    </div>
</section>

<script src="js/validacion.js"></script>

<script>
    window.addEventListener('load', (e) => {
        const usernameError = document.querySelector('div#usernameError');
        document.querySelector('input#username').addEventListener('input', validateListener(usernameError, { patternMismatch: 'Debe contener solo numeros y letras' }));

        const contrasenaError = document.querySelector('div#contrasenaError');
        document.querySelector('input#contrasena').addEventListener('input', validateListener(contrasenaError));

    })
</script>

<?php view('footer') ?>