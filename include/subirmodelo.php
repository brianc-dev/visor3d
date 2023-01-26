<?php view('header') ?>

<?php view('navbar') ?>

<!-- main section -->
<section class="subir-container container-md px-4 py-5">
    <div class="row row-cols-1 mx-5 px-4 py-4">
        <div class="col">
            <h1 class="text-center fw-bold">Selecciona el archivo:</h1>
        </div>
    </div>
    <div class="row row-cols-1">
        <div class="col-md-8 col-12 px-4">
            <form action="subirmodelo.php" method="post" enctype="multipart/form-data">
                <input class="form-control mb-3" type="file" name="modelo" id="modelo" accept="model/obj" required>
                <div class="form-floating">
                    <input class="form-control mb-3" type="text" name="nombre" id="nombre" placeholder="Nombre de modelo" required  minlength="2" maxlength="50">
                    <label for="nombre">Nombre o descripcion del modelo</label>
                </div>
                <button class="btn btn-success rounded-pill p-3" type="submit" value="subir">Subir modelo</button>
            </form>
        </div>
        <div class="col-md-4 col-12 px-4">
            <div class="card">
                <div class="card-header text-center">
                    Recomendaciones:
                </div>
                <div class="card-body">
                    <ul>
                        <li>Solo se permiten archivos con formato .obj</li>
                        <li>El tamano maximo es de 10 MB</li>
                        <li>Usa un titulo autentico y llamativo para tu modelo.</li>
                        <li>El nombre original del archivo es ignorado</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<?php view('footer') ?>