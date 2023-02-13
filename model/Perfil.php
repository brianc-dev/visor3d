<?php

namespace visor3d\model;

use \PDO;
use \Exception;

class PerfilModel {

    private const UPLOAD_DIR = 'uploads/profile/';
    private const TARGET_DIR = __DIR__ .  '/../' . self::UPLOAD_DIR;
    private $pdo;

    public function __CONSTRUCT() {
        try {
            global $_CONFIG;
            $db_host = $_CONFIG['db_host'];
            $db_name = $_CONFIG['db_name'];
            $db_user = $_CONFIG['db_user'];
            $db_password = $_CONFIG['db_password'];
            $this->pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    public function createPerfil(Perfil $perfil): void {
        try {
            $stm = $this->pdo->prepare('INSERT INTO `perfil`(`username`, `nombre`) VALUES(?, ?)');
            $stm->execute([
                $perfil->__GET('username'),
                $perfil->__GET('username')
            ]);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function consultarPerfil(string $username): ?Perfil {
        try {
            $stm = $this->pdo->prepare('SELECT * FROM `perfil` WHERE `username` = ?');
            $stm->execute([$username]);
            $result = $stm->fetch(PDO::FETCH_OBJ);

            if (!$result) {
                return null;
            }

            $perfil = new Perfil();

            $perfil->__SET('username', $result->username);
            $perfil->__SET('nombre', $result->nombre);
            $perfil->__SET('photo_url', ($result->photo_url) ? self::UPLOAD_DIR . $result->photo_url : null);
            $perfil->__SET('descripcion', $result->descripcion);
            $perfil->__SET('miembro_desde', $result->miembro_desde);

            return $perfil;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function modificarPerfil(Perfil $perfil): void {
        try {
            $stm = $this->pdo->prepare('UPDATE `perfil` SET `nombre` = ?, `descripcion` = ?, `photo_url` = ? WHERE `username` = ?');
            $stm->execute([
                $perfil->__GET('nombre'),
                $perfil->__GET('descripcion'),
                $perfil->__GET('photo_url'),
                $perfil->__GET('username')
            ]);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function modificarNombre(string $nombre) {
        try {
            $stm = $this->pdo->prepare('UPDATE `perfil` SET `descripcion` = ? WHERE `username` = ?');

            $stm->execute([
                $nombre,
                $_SESSION['username']
            ]);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function modificarDescripcion(string $descripcion): bool {
        try {
            $stm = $this->pdo->prepare('UPDATE `perfil` SET `descripcion` = ? WHERE `username` = ?');

            return $stm->execute([
                $descripcion,
                $_SESSION['username']
            ]);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    
    public function eliminarPerfil(string $username): bool {
        try {
            $stm = $this->pdo->prepare('DELETE FROM `perfil` WHERE `username` = ?');
            return $stm->execute([$username]);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function guardarFotoDePerfil(string $tmp): string {

        // delete previous profile photo file if file exists
        try {

            $stm = $this->pdo->prepare('SELECT `photo_url` FROM `perfil` WHERE `username` = ?');
            $stm->execute([
                $_SESSION['username']
            ]);

            $result = $stm->fetch(PDO::FETCH_OBJ);

            if ($result->photo_url) {
                $oldPhoto = self::TARGET_DIR . $result->photo_url;
                if (!\unlink($oldPhoto)) {
                    http_response_code(500);
                    die();
                }
            }
            $stm->closeCursor();

        } catch(Exception $e) {
            die($e->getMessage());
        }

        $urn = $this->generatePhotoId();
        
        $filepath = self::TARGET_DIR . $urn;
        
        if (!move_uploaded_file($tmp, $filepath)) {
            \http_response_code(500);
            die();
        }

        try {
            $stm = $this->pdo->prepare('UPDATE `perfil` SET `photo_url` = ? WHERE `username` = ?');
            $stm->execute([
                $urn,
                $_SESSION['username']
            ]) || die();

            return self::UPLOAD_DIR . $urn;

        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    private function generatePhotoId(): string {
        $stm = $this->pdo->prepare('SELECT `photo_url` FROM `perfil` WHERE `photo_url` = ?');
        
        // loop infinito, ejecuta esto hasta que se encuentre un id que no este ocupado
        do {
            $id = generateFileId() . '.png';
            // Si existe como archivo, entonces saltar al siguiente loop
            if (file_exists(self::TARGET_DIR . $id)) continue;
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

class Perfil {
    private string $username;
    private string $nombre;
    private ?string $photo_url;
    private string $descripcion;
    private string $miembro_desde;

    /**
     * Get the value for the specified property
     */
    public function __GET($k) { return $this->$k; }
    /**
     * Set the property k to the specified value v
     */
    public function __SET($k, $v) { return $this->$k = $v; }
}