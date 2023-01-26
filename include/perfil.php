<?php
if (!isset($perfil)) die('Ha ocurrido un error al cargar el perfil');
?>

<?php view('header') ?>

<?php view('navbar') ?>

<!-- profile section -->
<section class="profile container-fluid px-4 p-3 text-white">
    <div class="row px-4 mx-5">
        <div class="col-12 col-md-2">
            <div class="position-relative">
                <img class="profile__picture w-100 rounded-circle"
                    src="<?= $perfil->__GET('photo_url') ?? 'images/foto_perfil/610-6104451_placeholder.png'?>" alt="">
                <?php if (is_user_logged_in() && $perfil->__GET('username') === $_SESSION['username']): ?>
                <div class="profile__change-image w-100 rounded-circle" role="button" data-bs-toggle="modal"
                    data-bs-target="#changeProfilePhotoModal"><i class="bi-pencil-square"></i></div>
                <?php endif ?>
            </div>
        </div>
        <div class="col-12 col-md-10 d-flex flex-column justify-content-center">
            <div class="row pt-4 pt-md-0 align-items-center">
                <div class="col d-flex">
                    <h2>
                        <?= $perfil->__GET('nombre') ?>
                    </h2>
                </div>

            </div>
            <div class="row">
                <div class="col">
                    <h6 id="usernameHeader">@<?= $perfil->__GET('username') ?>
                    </h6>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <p>
                        <?= $perfil->__GET('descripcion') ?? '' ?>
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <p>Miembro desde:
                        <?php
                            echo date('d-m-Y', strtotime($perfil->__GET('miembro_desde')))
                         ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="container py-4 mt-4 px-4">
    <div class="row px-4 mx-4">
        <div class="col"><h1>Modelos</h1><hr></div>
    </div>
</section>

<section id="empty-user" class="container py-5">
    <div class="row row-cols-1">
        <div class="col text-center"><span>Noy hay modelos a visualizar. </span><a class="text-decoration-underline" href="subirmodelo.php">Agrega un modelo ahora!</a></div>
    </div>
</section>

<!-- models section -->
<section class="container-md px-4">
    <div id="models-container" class="models-container row row-cols-1 row-cols-md-3 gy-4 px-4 mx-4">

    </div>

    <div class="models-actions row mt-3 mx-4 px-4 py-4">
        <div class="d-flex align-items-center col">
            <button id="loadmore-button" class='btn btn-outline-info d-none' disabled>Cargar mas</button>
            <div class=" text-primary px-4"><b id="models-count">0</b> de <b id="models-total">0</b> modelos</div>
        </div>
    </div>
</section>
<script src="./js/perfil.js"></script>

<?php if (is_user_logged_in() && $perfil->__GET('username') === $_SESSION['username']): ?>
<!-- modal -->
<div class="change-profile-photo-modal modal fade" id="changeProfilePhotoModal" tabindex="-1"
    aria-labelledby="changeProfilePhotoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="changeProfilePhotoLabel">Cambiar foto de perfil</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row row-cols-1">
                    <div class="col">
                        <div class="position-relative d-flex justify-content-center">
                            <img class="change-profile-photo-modal__image w-75 rounded-circle"
                                src="<?= $perfil->__GET('photo_url') ?? 'images/foto_perfil/610-6104451_placeholder.png'?>"
                                alt="">
                            <div id="selectProfilePhotoButton"
                                class="change-profile-photo-modal__button w-75 rounded-circle" role="button"><i
                                    class="bi-pencil-square"></i></div>
                        </div>
                    </div>
                </div>
                <input class="d-none" type="file" id="profilePhotoInput" name="profilePhoto"
                    accept="image/png, image/jpg, image/jpeg" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button id="submitPhotoButton" type="button" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>
<?php endif ?>

<?php view('footer') ?>