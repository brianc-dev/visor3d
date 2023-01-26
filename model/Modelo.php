<?php

namespace visor3d\model;

use \PDO;
use \Exception;

/**
 * Esta clase maneja los modelos 3D de cada usuario
 */
class ModeloModel {
    
    private const TARGET_DIR = __DIR__ . '/../uploads/modelos';
    private const THUMBNAILS_TARGET_DIR = __DIR__ . '/../uploads/modelos_thumbnail/';
    private const THUMBNAILS_FORMAT = '.png';
    
    private $pdo;

    public function __CONSTRUCT() {
        try {
            $this->pdo = new PDO('mysql:host=localhost;dbname=visor3d', 'root', '');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function guardarModelo(string $tmp, string $nombre): string {
        // Genera un nombre y luego mueve el archivo alli
        $nombre_archivo = $this->generateUniqueId();

        $filepath = self::TARGET_DIR . '/' . $nombre_archivo;

        // Mueve el archivo del directorio tmp al directorio final
        if (!move_uploaded_file($tmp, $filepath)) {
            \redirect_with_message('subirmodelo.php', 'Ocurrio un error al intentar guardar el archivo', \FLASH_ERROR);
        }

        try {
            $modelo = new Modelo();
            $modelo->__SET('modelo_url', $nombre_archivo);
            $modelo->__SET('nombre', $nombre);
            $modelo->__SET('username', $_SESSION['username']);

            $stm = $this->pdo->prepare('INSERT INTO `modelo`(`username`, `nombre`, `modelo_url`) VALUES(?, ?, ?)');

            $res = $stm->execute([
                $modelo->__GET('username'),
                $modelo->__GET('nombre'),
                $modelo->__GET('modelo_url')
            ]);

            if ($res) return \substr($nombre_archivo, 0, strlen($nombre_archivo) - 4);
            
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function consultarModelosPorUsername(string $username): array {
        try {
            $stm = $this->pdo->prepare('SELECT * FROM `modelo` WHERE `username` = ?');
            $result = $stm->execute([$username]);

            if (!$result) die('Ha ocurrido un error');

            $resultSet = $stm->fetchAll(PDO::FETCH_OBJ);

            $result = [];
            
            foreach ($resultSet as $n => $item) {
                $modelo = new Modelo();
                $modelo->__SET('username', $item->username);
                $modelo->__SET('nombre', $item->nombre);
                $modelo->__SET('modelo_url', $item->modelo_url);
                $modelo->__SET('thumbnail_url', $item->thumbnail_url);

                $result[] = $modelo;
            }

            return $result;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function consultarModeloPorId(string $id): ?Modelo {
        $modelo_url = $id . '.obj';
        try {
            $stm = $this->pdo->prepare('SELECT * FROM `modelo` WHERE `modelo_url` = ?');
            $stm->execute([
                $modelo_url
            ]) || die('Ocurrio un error al intentar consultar la base de datos');

            if ($stm->fetchColumn() === false) {
                return null;
            }
            
            $stm->execute([
                $modelo_url
            ]);
            $resultSet = $stm->fetch(PDO::FETCH_OBJ);

            $modelo = new Modelo();
            $modelo->__SET('username', $resultSet->username);
            $modelo->__SET('nombre', $resultSet->nombre);
            $modelo->__SET('modelo_url', $resultSet->modelo_url);
            $modelo->__SET('thumbnail_url', $resultSet->thumbnail_url);
            
            return $modelo;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function eliminarModelo(string $modelo_url): bool {
        try {
            $stm = $this->pdo->prepare('DELETE FROM `modelo` WHERE `username` = ? AND `modelo_url` = ?');

            return $stm->execute([
                $_SESSION['username'],
                $modelo_url
            ]);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function createThumbnail(string $modeloUrl, string $imageUrl): void {
        try {
            // Process image
            $image = \imagecreatefrompng($imageUrl); // Create image from url
            $croppedImage = \imagecropauto($image, \IMG_CROP_BLACK); // Then crop from to rectangle from black background

            $tmpImageFile = \tempnam(\sys_get_temp_dir(),'tmp_image'); // Save to temp file so we can get info
            \imagepng($croppedImage, $tmpImageFile);

            $imageInfo = \getimagesize($tmpImageFile); // Get info from image temp file
            $width = $imageInfo[0]; // 0 for width
            $height = $imageInfo[1]; // 1 for height

            $newImage = \imagecreatetruecolor(350, 300); // Create new image of 350x300
            $color = \imagecolorallocate($newImage, 0, 0, 0); // Create black color
            \imagefill($newImage, 0, 0, $color); // Fill image with color

            if ($width > $height) {
                $scaledImage = imagescale($croppedImage, 350); // scale cropped image to width = 350 and keep ratio, that's why we don't define height
                \imagedestroy($croppedImage);
                $tmpImageFile2 = \tempnam(\sys_get_temp_dir(), 'tmp_image'); // Create another temp file
                \imagepng($scaledImage, $tmpImageFile2); // Save

                $newImageInfo = \getimagesize($tmpImageFile2); // Get info for scaled image
                $newWidth = $newImageInfo[0];
                $newHeight = $newImageInfo[1];

                // Copy scaled image to image created and filled previously
                // We make sure it copied in the center of new image
                \imagecopy($newImage, $scaledImage, 0, 300 / 2 - $newHeight / 2, 0, 0, $newWidth, $newHeight);
                \imagedestroy($scaledImage);
            } else {
                $rotatedImage = \imagerotate($croppedImage, 90, 0);
                \imagedestroy($croppedImage);
                $scaledImage = \imagescale($rotatedImage, 300);
                \imagedestroy($rotatedImage);
                $rotatedImage = \imagerotate($scaledImage, 360-90, 0);
                \imagedestroy($scaledImage);

                $tmpImageFile2 = \tempnam(\sys_get_temp_dir(), 'tmp_image');
                \imagepng($rotatedImage, $tmpImageFile2);

                $newImageInfo = \getimagesize($tmpImageFile2);
                $newWidth = $newImageInfo[0];
                $newHeight = $newImageInfo[1];
                \imagecopy($newImage, $rotatedImage, 350 / 2 - $newWidth / 2, 0, 0, 0, $newWidth, $newHeight);
                \imagedestroy($rotatedImage);
            }
            //Save to file
            // $imageFile = \tempnam(self::THUMBNAILS_TARGET_DIR, '');
            do {
                $imageFile = self::THUMBNAILS_TARGET_DIR . \randomId() . self::THUMBNAILS_FORMAT;
                if (!\file_exists($imageFile)) break;
            } while (true);
            \imagepng($newImage, $imageFile) or throw new Exception("Error guardando la imagen", 2);
            // \rename($imageFile, $imageFile . '.png');
            // ie: abcdefg.obj
            $thumbnailUrl = \basename($imageFile);

            // Save to database
            $stm = $this->pdo->prepare('UPDATE `modelo` SET `thumbnail_url` = ? WHERE `username` = ? AND `modelo_url` = ?');

            $stm->execute([
                $thumbnailUrl,
                $_SESSION['username'],
                $modeloUrl
            ]) or throw new Exception("Error Processing Request", 1);
        } catch (Exception $e) {
            die($e->getMessage());
        } finally {
            $stm->closeCursor();
        }
    }
    
    /**
     * Genera un id unico para un archivo. Esta funcion comprueba que el Id generado sea unico en la base de datos y el sistema de archivos;
     * @return string El id unico
     */
    private function generateUniqueId(): string {
        $stm = $this->pdo->prepare('SELECT `modelo_url` FROM `modelo` WHERE `modelo_url` = ?');
        
        // loop infinito, ejecuta esto hasta que se encuentre un id que no este ocupado
        do {
            $id = generateFileId() . '.obj';
            // Si existe como archivo, entonces saltar al siguiente loop
            if (file_exists(__DIR__ . '/../uploads/modelos/' . $id)) continue;
            // Chequea que la consulta se realizo
            if (!$stm->execute([$id])) {
                die("Error al buscar en la base de datos");
            }
            // Si retorna false, entonces no existen registros
            // Y devuelve el id
            if ($stm->fetchColumn() === false) return $id;
        } while (true);
    }
}

class Modelo {
    private $username;
    private $nombre;
    private $modelo_url;
    private $thumbnail_url;

    public function __GET($k) { return $this->$k; }
    public function __SET($k, $v) {return $this->$k = $v; }
}