<?php view('header') ?>

<?php view('navbar') ?>

<!-- main section -->
<?php view('viewer', ['modelo' => $modelo, 'thumbnail' => $thumbnail]) ?>

<!-- instrucciones -->
<?php view('instrucciones') ?>

<?php view('descripcion', ['username' => $username, 'modelo' => $modelo, 'descripcion' => $descripcion]) ?>

<?php view('footer') ?>