<section class="descripcion container-fluid px-4 pb-4">
    <div class="row">
        <div class="col-12 align-text-middle">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <?= $descripcion ?>
                    </h5>
                    <hr>
                    <h6 class="card-subtitle mb-2 text-muted pb-3"><?= $username ?></h6>
                    <?php if (is_user_logged_in() && $username === $_SESSION['username']):  ?>
                    <form action="eliminarModelo.php" method="post">
                        <button class="btn btn-danger rounded-pill" type="submit" name='modelo' value="<?= $modelo ?>">Eliminar
                            modelo</button>
                    </form>
                    <?php endif ?>
                </div>
            </div>

        </div>
    </div>
</section>