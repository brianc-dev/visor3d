<nav class="navbar px-4  <?= is_user_logged_in() ? 'navbar-expand' : 'navbar-expand-md' ?> fixed-top">
        <div class="container-fluid mx-5">
            <a class="navbar-brand d-flex" href="index.php">
                <img src="images/logo.png" alt=""><h1 class="title fw-bold py-2">Visor3D</h1>
            </a>
            <?php if (!is_user_logged_in()) { ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <?php } else 
            { ?><ul class="navbar-nav ms-auto mb-0">
                <li class="nav-item">
                    <div class="dropdown">
                        <a class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                            <img width="40px" class="navbar__picture rounded-circle"
                                src="<?= find_profile_by_username($_SESSION['username'])->__GET('photo_url') ?? 'images/foto_perfil/610-6104451_placeholder.png' ?>"
                                alt="profile-photo">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <h6 class="dropdown-item disabled">
                                    <?= mb_strimwidth(find_user_by_username($_SESSION['username'])->__GET('username'),0, 15, '...') ?>
                                </h6>
                            </li>
                            <li><a class="dropdown-item" href="subirmodelo.php">Subir modelo <i
                                        class="bi-upload ms-1"></i></a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="perfil.php">Perfil</a></li>
                            <li><a class="dropdown-item" href="configuracion.php">Configuracion</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="logout.php">Cerrar sesion</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
            <?php } ?>
            <?php if (!is_user_logged_in()): ?>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link btn rounded-pill text-white fw-bold px-4 mx-2" aria-current="page" href="standalone.php">Visualizador</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn rounded-pill text-white fw-bold px-4 mx-2" aria-current="page" href="iniciarsesion.php">Iniciar sesion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-info rounded-pill text-white fw-bold px-4 ms-2" aria-current="page" href="registrarse.php">Registrarse</a>
                    </li>
                </ul>
            </div>
            <?php endif ?>
        </div>
    </nav>