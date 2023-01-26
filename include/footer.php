<footer>
    <div class="container-fluid p-0 mt-4">
        <div class="social-media-footer d-flex justify-content-center align-items-center">
            <div id="social-media" class="container">
                <div class="row">
                    <div class="col-3">
                        <a href="#"><img src="./images/mail.svg" alt="mail"></a>
                    </div>
                    <div class="col-3">
                        <a href="#"><img src="./images/instagram.svg" alt="instagram"></a>
                    </div>
                    <div class="col-3">
                        <a href="#"><img src="./images/facebook.svg" alt="facebook"></a>
                    </div>
                    <div class="col-3">
                        <a href="#"><img src="./images/twitter.svg" alt="twitter"></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container px-4 px-md-0 text-white fw-bold">
            <div class="row content-footer align-items-center px-4 mx-5">
                <div class="col-12 col-md-4 my-4">
                    <h5 class="fw-bold">Visor3D</h5>
                    <p class="fw-bold">Visor3D es una aplicacion web que permite visualizar modelos 3D en el
                        formato .obj, escalar, rotar y cambiar el material del objeto.
                        Tambien puedes crear tu propia cuenta y guardas modelos 3D para visualizar donde quieras y
                        cuando quieras.</p>
                </div>
                <div class="col-12 col-md-4 offset-md-4">
                    <div class="col">
                        <div class="d-flex flex-column justify-content-start">

                            <a href="standalone.php">
                                <p>Visualizador</p>
                            </a>
                            <a href="#">
                                <p>Contacto</p>
                            </a>
                            <a href="./terminos.php">
                                <p>Terminos de uso</p>
                            </a>
                            <a href="./privacidad.php">
                                <p>Politica de privacidad</p>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-4 my-md-0">
                <div class="col">
                    <div class="horizontal-bar"></div>
                </div>
            </div>
            <div class="row py-4 align-items-center">
                <div class="col">
                    <div class="d-flex justify-content-center">
                        <p class="">&copy; 2023 Visor3D</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</footer>

<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <?php display_all_flash_messages() ?>
</div>
<script src="./js/popper.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script src="js/toast.js"></script>
</body>

</html>