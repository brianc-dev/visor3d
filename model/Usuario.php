<?php

namespace visor3d\model;

use \PDO;
use \Exception;

class UsuarioModel {
    private $pdo;

    public function __CONSTRUCT() {
        try {
            $this->pdo = new PDO('mysql:host=localhost;dbname=visor3d', 'root', '');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function consultarUsuarios() {
    try {
        $result = array();

        $stm = $this->pdo->prepare("SELECT * from `usuario`");
        $stm->execute();

        foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
            $user = new Usuario();

            $user->__SET('username', $r->username);
            $user->__SET('email', $r->email);
            $user->__SET('contrasena', $r->contrasena);
            $user->__SET('creado_en', $r->creado_en);
            $user->__SET('is_admin', $r->is_admin);


            $result[] = $user;
        }

        return $result;
    } catch (Exception $e) 
    {
        die($e->getMessage());
    }
}

    public function recuperarUsuario(string $username) {
        try {
            $stm = $this->pdo->prepare("SELECT * FROM `usuario` WHERE `username` = ?");
            $stm->execute(array($username));
            $result = $stm->fetch(PDO::FETCH_OBJ);

            if (!$result) {
                return null;
            }

            $usuario = new Usuario();

            $usuario->__SET('username', $result->username);
            $usuario->__SET('email', $result->email);
            $usuario->__SET('contrasena', $result->contrasena);
            $usuario->__SET('creado_en', $result->creado_en);
            $usuario->__SET('is_admin', $result->is_admin);

            return $usuario;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function guardarUsuario(Usuario $usuario) {
        try {
            $sql = "INSERT INTO `usuario`(`username`, `email`, `contrasena`) VALUES (?, ?, ?)";

            $this->pdo->prepare($sql)->execute(array(
                $usuario->__GET('username'),
                $usuario->__GET('email'),
                password_hash($usuario->__GET('contrasena'), PASSWORD_BCRYPT)
            ));
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function modificarUsuario(Usuario $usuario) {
        try {
            $sql = "UPDATE `usuario` SET 'email' = ?, 'contrasena' = ? WHERE 'username' = ?";

            $this->pdo->prepare($sql)->execute(array(
                $usuario->__GET('email'),
                password_hash($usuario->__GET('contrasena'), PASSWORD_BCRYPT),
                $usuario->__GET('username')
            ));
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function eliminarUsuario($username) {
        try {
            $sql = "DELETE FROM `usuario` WHERE 'username' = ?";

            $this->pdo->prepare($sql)->execute(array(
                $username
            ));
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function cambiarContrasena(string $contrasenaActual, string $contrasenaNueva, string $contrasenaConfirmacion) {
        try {
            $stm = $this->pdo->prepare('SELECT `contrasena` FROM `usuario` WHERE `username` = ?');
            $stm->execute([
                $_SESSION['username']
            ]) || die('Error al consultar base de datos');

            $resultSet = $stm->fetch(PDO::FETCH_OBJ);

            if (!\password_verify($contrasenaActual, $resultSet->contrasena)) {
                throw new Exception('La contrasena ingresada no coincide con la contrasena actual del usuario', 1);
            }

            if (\strcmp($contrasenaNueva, $contrasenaConfirmacion) !== 0) {
                throw new Exception('Las contrasenas nuevas no coinciden', 2);
            }

            $stm->closeCursor();

            $stm = $this->pdo->prepare('UPDATE `usuario` SET `contrasena` = ? WHERE `username` = ?');

            $contrasenaNuevaHash = \password_hash($contrasenaNueva, PASSWORD_BCRYPT);

            $stm->execute([
                $contrasenaNuevaHash,
                $_SESSION['username']
            ]);

        } catch (Exception $e) {
            switch ($e->getCode()) {
                case 1:
                case 2:
                    throw $e;
                    break;
                default:
                break;
            }
            die($e->getMessage());
        }
    }
}

class Usuario {
    private $username;
    private $email;
    private $contrasena;
    private $is_admin;
    private $creado_en;

    public function __GET($k) { return $this->$k; }
    /**
     * Set the property k to the specified value v
     */
    public function __SET($k, $v) { return $this->$k = $v; }
}