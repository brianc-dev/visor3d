<?php view('header') ?>

<?php view('navbar') ?>

<!-- header -->
<section class="lp-section-1 container-fluid px-4 py-5">
    <div class="row row-cols-md-2 row-cols-1 mx-5 px-4 py-4">
        <div class="col d-flex flex-column justify-content-center text-white p-4">
            <div class="mb-2 fw-bold">

                <h1 class="mb-4 fw-bold">Mira tus disenos 3D favoritos. Accede a ellos donde quieras y cuando quieras
                </h1>
                <p class="">Utiliza nuestras herramientas interactivas para inspeccionar tu modelo a fondo. Gira, escala
                    y
                    desplazate a traves del entorno 3D. Y cuando encuentres un angulo que te llame la atencion, toma una
                    captura y guardala en tu equipo!</p>
            </div>
            <div class="d-flex mt-4">
                <a class="p-4 my-2 btn btn-success border-0 rounded-pill fw-bold" href="registrarse.php">Crea una cuenta
                    ahora!</a>

            </div>
        </div>
        <div class="col d-flex justify-content-center p-4"><img class="img-fluid" src="images/model2.png" alt=""></div>
    </div>
</section>

<!-- subheader -->
<section class="lp-section-2 container-fluid px-4 py-5">
    <div class="px-0 px-lg-5 mx-0 mx-lg-5">

        <div class="row row-cols-1 py-5">
            <div class="col text-center pt-4">
                <h2 class="lp-section-2__header fw-bold">NUESTRAS HERRAMIENTAS A TU DISPOSICION</h2>
            </div>
        </div>
        <div class="row row-cols-1 row-cols-md-3 mx-lg-5 mx-0 gx-5 gy-5 px-0 px-lg-4">
            <div class="col">
                <div class="card text-center shadow h-100 px-4 py-5">
                    <div class="card-body">
                        <img class="w-25 card-img-top " src="images/vision-icon.png" alt="">
                        <h5 class="fw-bold mt-4 mb-3 lp-section-2__header-text">Vision 360°</h5>
                        <p class="text-muted">Visualiza desde cualquier angulo y perspectiva. No pierdas ningun detalle
                            del modelo</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center shadow h-100 px-4 py-5">
                    <div class="card-body">
                        <img class="w-25 card-img-top " src="images/account-icon.png" alt="">
                        <h5 class="fw-bold mt-4 mb-3 lp-section-2__header-text">Cuenta</h5>
                        <p class="text-muted">Crea una cuenta y guarda los modelos que quieras. Puedes visualizarlos
                            cuando desees.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center shadow h-100 px-4 py-5">
                    <div class="card-body">
                        <img class="w-25 card-img-top " src="images/cog-icon.png" alt="">
                        <h5 class="fw-bold mt-4 mb-3 lp-section-2__header-text">Modifica el modelo</h5>
                        <p class="text-muted">Cambia las caracteristicas del modelo y del ambiente, y prueba distintos
                            tipos de renderizado. Ninguno de estos cambios modificara tu modelo.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="px-0 px-lg-5 mx-0 mx-lg-5">

        <div class="row row-cols-1 row-cols-md-3 mx-0 mx-lg-5 gx-5 gy-5 px-4 pt-5 justify-content-center">
            <div class="col">
                <div class="card text-center shadow h-100 px-4 py-5">
                    <div class="card-body">
                        <img class="w-25 card-img-top " src="images/screenshot-icon.png" alt="">
                        <h5 class="fw-bold mt-4 lp-section-2__header-text">Captura</h5>
                        <p class="text-muted">Toma capturas de pantalla de tu modelo y guardalos en tu dispositivo</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center shadow h-100 px-4 py-5">
                    <div class="card-body">
                        <img class="w-25 card-img-top " src="images/share-icon.png" alt="">
                        <h5 class="fw-bold mt-4 lp-section-2__header-text">Comparte con otros</h5>
                        <p class="text-muted">Copia el link de tu modelo y compartelo con otros.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row py-4 my-2"></div>
</section>

<!-- 3rd section -->
<section class="lp-section-3 container-fluid px-4 py-5">
    <div class="row row-cols-1 row-cols-md-2 mx-5 px-4 py-4">
        <div class="col p-4">
            <img class="img-fluid" src="images/model1.jpg" alt="">
        </div>
        <div class="col d-flex flex-column justify-content-center text-white p-4">
            <h6 class="lp-section-3__header-companion">Mundo 3D</h6>
            <h2 class="fw-bold">Un visualizador hecho a tu medida</h2>
            <p>El diseno 3D ha incursionado en toda variedad de campos, desde la arquitectura, medicina, mecanica hasta
                las peliculas, animaciones y videojuegos.</p>
            <p>No esperes mas y unete tu tambien al mundo del diseno 3D!</p>
            <div class="d-flex mt-4">
                <a class="p-4 my-2 btn btn-success border-0 rounded-pill fw-bold" href="registrarse.php">Unete
                    ahora!</a>
            </div>
        </div>
    </div>
</section>

<!-- 4th section -->
<section class="lp-section-4 container-fluid px-4 py-5">
    <div class="row row-cols-1">
        <div class="col text-center">
            <h2 class="lp-section-4__header-text fw-bold">Preguntas frecuentes</h2>
        </div>
    </div>
    <div class="row row-cols-1 mx-5 px-4 py-4">
        <div class="col">
            <div class="accordion accordion-flush" id="preguntasFrecuentesAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                            Que modelos puedo visualizar?
                        </button>
                    </h2>
                    <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
                        data-bs-parent="#preguntasFrecuentesAccordion">
                        <div class="accordion-body">Puedes visualizar modelos cuyos formatos sean <code>.obj</code>. Soporte para otros tipos de formatos esta planeado para el futuro.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                            Debo pagar para poder guardar modelos en mi cuenta?
                        </button>
                    </h2>
                    <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo"
                        data-bs-parent="#preguntasFrecuentesAccordion">
                        <div class="accordion-body">No. Puedes almacenar modelos en tu cuenta totalmente gratis.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseThree" aria-expanded="false"
                            aria-controls="flush-collapseThree">
                            Hay algun limite en la cantidad de modelos?
                        </button>
                    </h2>
                    <div id="flush-collapseThree" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingThree" data-bs-parent="#preguntasFrecuentesAccordion">
                        <div class="accordion-body">Puedes almacenar cuantos modelos desees. Pero deben pesar 10MB o menos.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingFour">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseFour" aria-expanded="false"
                            aria-controls="flush-collapseFour">
                            Pueden los demas ver mis modelos?
                        </button>
                    </h2>
                    <div id="flush-collapseFour" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingFour" data-bs-parent="#preguntasFrecuentesAccordion">
                        <div class="accordion-body">Si. Todos los modelos son publicamente visibles. Pero solo tu puedes eliminarlos.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php view('footer') ?>