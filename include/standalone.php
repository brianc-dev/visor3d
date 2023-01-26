<?php view('header') ?>
<?php view('navbar') ?>

<?php view('viewer') ?>

<div class="container">
    <div class="row">
        <div class="col-auto">
            <label for="tempModelFile" class="mb-2 input-group-text">Subir modelo</label>
            <input class="pb-4" type="file" name="tempModelFile" id="tempModelFile" accept="model/obj">
            <button id="tempModelFileButton" class="btn btn-outline-primary">Upload</button>
        </div>
    </div>
</div>


<script src="js/standalone.js"></script>

<?php view('footer') ?>