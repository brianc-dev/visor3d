<section class="container-fluid py-4">
    <div class="row gy-3">
        <div class="col-12 col-md-8">
            <div id="canvas-placeholder">
            </div>
        </div>
        <div class="col-12 col-md-4">
            <?php view('controls') ?>
        </div>
    </div>
</section>

<script>
    const modelFile = "<?= $modelo ?>";
    const requestsThumbnail = <?= ($thumbnail) ? 'false' : 'true' ?>;
</script>
<script src="js/p5.js"></script>
<script src="js/viewer.js"></script>