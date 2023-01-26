<?php

view('header');

view('navbar');

?>

<section class="config container-md px-4 py-4">
    <div class="row py-4">
        <div class="col"><h1>Configuracion</h1></div>
    </div>
    <div class="row">
        <div class="col d-flex flex-column">

        </div>
    </div>
    <div class="row">
        <div class="col">
            
        </div>
    </div>
    <div class="row">
        <div class="col">
            <h2>Configuracion de perfil</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-1 d-none">
            <label class="form-label" for="nombre">Nombre</label>
        </div>
        <div class="col-1 d-none">
            <input value="<?= $nombre ?>" id="nombre" class="form-control-plaintext" type="text" readonly required
                minlength="1" maxlength="35">
        </div>
        <div class="col-1 d-none">
            <button id="editarNombre" class="config__button btn btn-sm btn-primary">Editar</button>
        </div>
    </div>

    <div class="row pt-4">
        <div class="col col-md-4">
            <div class="form-floating">
                <textarea class="form-control" placeholder="Leave a comment here" id="descripcion"
                    style="resize: none;" disabled minlength="0" maxlength="60"><?= $descripcion ?? '' ?></textarea>
                <label for="descripcion">descripcion</label>
                <button id="cancelarDescripcionButton" class="btn btn-secondary d-none pt-4" type="submit">Cancelar</button>
                <button id="descripcionButton" class="btn btn-primary mt-2" type="submit"><span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>Editar
                    
                </button>
            </div>
        </div>
    </div>
    <div class="row py-4">
        <div class="col">
            <h2>Configuracion de usuario</h2>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <h3>Contrasena</h3>
            <button class="btn btn-danger rounded-pill" type="button" data-bs-toggle="modal" data-bs-target="#cambiarContrasenaModal">Cambiar contrasena</button>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="cambiarContrasenaModal" tabindex="-1" aria-labelledby="cambiarContrasenaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Cambiar contrasena</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body align-items-center text-end">
            <div class="row my-2">
                <div class="col-4">
                    <label for="contrasenaActualInput" class="col-form-label">Contrasena actual</label>
                </div>
                <div class="col">
                    <input class="form-control" id="contrasenaActualInput" type="password" required maxlength="64" minlength="8" autocomplete="current-password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$=!%*?&])[A-Za-z\d@$=!%*?&]{8,64}$">
                    <div id="contrasenaActualError" class=""></div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-4">
                    <label for="contrasenaNuevaInput" class="col-form-label">Contrasena nueva</label>
                </div>
                <div class="col">
                    <input class="form-control" id="contrasenaNuevaInput" type="password" required maxlength="64" minlength="8" autocomplete="new-password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$=!%*?&])[A-Za-z\d@$=!%*?&]{8,64}$">
                    <div id="contrasenaNuevaError" class=""></div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-4">
                    <label for="contrasenaConfirmacionInput" class="col-form-label">Confirma contrasena</label>
                </div>
                <div class="col">
                    <input class="form-control" id="contrasenaConfirmacionInput" type="password" required autocomplete="new-password">
                    <div id="contrasenaConfirmacionError" class=""></div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button id="cambiarContrasenaButton" type="button" class="btn btn-success">
            <span id="contrasenaSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            Cambiar contrasena</button>
        </div>
      </div>
    </div>
  </div>

<script src="js/configuration.js"></script>

<?php view('footer') ?>