<?php

class BaseClass {

    // coordonnée sur X
    private $_currentX;

    // coordonnée sur Y
    private $_currentY;

    // angle de vue
    private $_currentAngle;

    // status action
    private $_statusAction;

    // connexion à la base des données
    private $_dbh;

    
    // methode construct pour la connexion à la base
    public function __construct() {
        $this->_dbh = new DataBase;
    }

    public function setCurrentX(int $_currentX) {
        if (is_int($_currentX) && $_currentX >= 0 && $_currentX <= 1) {
            $this->_currentX = $_currentX;
        }    
    }

    public function getCurrentX() {
        return $this->_currentX;
    }

    public function setCurrentY(int $_currentY) {
        if (is_int($_currentY) && $_currentY >= 0 && $_currentY <= 2) {
            $this->_currentY = $_currentY;
        }    
    }

    public function getCurrentY() {
        return $this->_currentY;
    }

    public function setCurrentAngle(int $_currentAngle) {
        if ($_currentAngle >= 0 && $_currentAngle <= 270) {
            $this->_currentAngle = $_currentAngle;
        }    
    }

    public function getcurrentAngle() {
        return $this->_currentAngle;
    }

    public function setStatusAction(int $_statusAction) {
        if (is_int($_statusAction) && $_statusAction === 0 || is_int($_statusAction) && $_statusAction === 1) {
            $this->_statusAction = $_statusAction;
        }
    }

    public function getStatusAction() {
        return $this->_statusAction;
    }


    // methode privée pour interroger la base de données et vérifier que le mouvement vers les coordonnées cibles est bien possible
    private function _checkMove($_currentX, $_currentY, $_currentAngle) {
        // error_log(print_r("X:" .$_currentX, 1));
        // error_log(print_r("Y:" .$_currentY, 1));
        // error_log(print_r("angle:" .$_currentAngle, 1));
        $checkBase = "SELECT * FROM map WHERE coordx=:currentX AND coordy=:currentY AND direction=:currentAngle";
        $stmt = $this->_dbh->prepare($checkBase);
        $stmt->bindParam(':currentX', $_currentX, PDO::PARAM_INT);
        $stmt->bindParam(':currentY', $_currentY, PDO::PARAM_INT);
        $stmt->bindParam(':currentAngle', $_currentAngle, PDO::PARAM_INT);
        $stmt->execute();
        $arrResult = $stmt->fetchAll(PDO::FETCH_OBJ);
        //error_log("result check move ".print_r($arrResult, 1));
        
        if(!empty($arrResult)) {
            // error_log('array Result: true');
            return true;
        } else {
            // error_log('array Result: false');
            return false;
        }  
    }

    // methode pour pouvoir utiliser la methode privée CheckMove dans index.php
    public function checkCheck($_currentX, $_currentY, $_currentAngle) {
        return $this->_checkMove($_currentX, $_currentY, $_currentAngle);
    }
    
    // Vérifie la possibilité de déplacement vers l’avant
    public function checkForward($_currentX, $_currentY, $_currentAngle) {

        $newX = $_currentX;
        $newY = $_currentY;

        switch($_currentAngle) {
            case 90:
                $newY++;
            break;
            case 0:
                $newX++;
            break;
            case 270:
                $newY--;
            break;
            case 180:
                $newX--;
            break;
        }

        if ($this->_checkMove($newX, $newY, $_currentAngle) == true) {
            // error_log('checkForward: true');
            return true; 
        } else {
            // error_log('checkForward: false');
            return false;  
        }
    }

    // Vérifie la possibilité de déplacement vers l’arrière
    public function checkBack($_currentX, $_currentY, $_currentAngle) {

        $newX = $_currentX;
        $newY = $_currentY;

        switch($_currentAngle) {
            case 90:
                $newY--;
            break;
            case 0:
                $newX--;
            break;
            case 270:
                $newY++;
            break;
            case 180:
                $newX++;
            break;
        }

        if ($this->_checkMove($newX, $newY, $_currentAngle) == true) {
            // error_log('checkBack: true');
            return true;
        } else {
            // error_log('checkBack: false');
            return false;
        }
    }

    // Vérifie la possibilité de déplacement vers la droite
    public function checkRight($_currentX, $_currentY, $_currentAngle) {

        $newX = $_currentX;
        $newY = $_currentY;

        switch($_currentAngle) {
            case 90:
                $newX++;
            break;
            case 0:
                $newY--;
            break;
            case 270:
                $newX--;
            break;
            case 180:
                $newY++;
            break;
        }

        if ($this->_checkMove($newX, $newY, $_currentAngle) == true) {
            // error_log('checkRight: true');
            return true;
        } else {
            // error_log('checkRight: false');
            return false;
        }
    }

    // // Vérifie la possibilité de déplacement vers la gauche
    public function checkLeft($_currentX, $_currentY, $_currentAngle) {

        $newX = $_currentX;
        $newY = $_currentY;

        switch($_currentAngle) {
            case 90:
                $newX--;
            break;
            case 0:
                $newY++;;
            break;
            case 270:
                $newX++;
            break;
            case 180:
                $newY--;
            break;
        }

        if ($this->_checkMove($newX, $newY, $_currentAngle) == true) {
            // error_log('checkLeft: true');
            return true;
        } else {
            // error_log('checkLeft: false');
            return false;
        }
    }

    // // Vérifie la possibilité de tourner à droite
    public function checkTurnRight($_currentX, $_currentY, $_currentAngle) {

        $newAngle = $_currentAngle;

        switch($_currentAngle) {
            case 0 :
                $newAngle = 270;
            break;
            case 90:
                $newAngle = 0;
            break;
            case 180:
                $newAngle = 90;
            break;
            case 270:
                $newAngle = 180;
            break;
        }

        if ($this->_checkMove($_currentX, $_currentY, $newAngle) == true) {
            // error_log('checkTurnRight: true');
            return true;
        } else {
            // error_log('checkTurnRight: false');
            return false;
        }
    }

    // // Vérifie la possibilité de tourner à gauche
    public function checkTurnLeft($_currentX, $_currentY, $_currentAngle) {

        $newAngle = $_currentAngle;
        
        switch($_currentAngle) {
            case 0:
                $newAngle = 90;
            break;
            case 90:
                $newAngle = 180;
            break;
            case 180:
                $newAngle = 270;
            break;
            case 270:
                $newAngle = 0;
            break;
        }

        if ($this->_checkMove($_currentX, $_currentY, $newAngle) == true) {
            // error_log('checkTurnLeft: true');
            return true;
        } else {
            // error_log('checkTurnLeft: false');
            return false;
        }  
    }

    // methodes permettant d'effectuer un deplacement
    // Effectue le déplacement vers l’avant
    public function goForward($_currentAngle) {
        
        switch($_currentAngle) {
            case 90:
                ++$this->_currentY;
            break;
            case 0:
                ++$this->_currentX;
            break;
            case 270:
                --$this->_currentY;
            break;
            case 180:
                --$this->_currentX;
            break;
        }

        // if ($this->checkForward($this->_currentX, $this->_currentY, $this->_currentAngle)) {
        //     // $this->setCurrentX($newX);
        //     // $this->setCurrentY($newY);
        
        //     error_log(print_r('goForward X:' .$this->_currentX, 1));
        //     error_log(print_r('goForward Y:' .$this->_currentY, 1));
        //     error_log(print_r('goForward Angle:' .$this->_currentAngle, 1));
        // } else { 
        //     error_log("Deplacement avant n'a pas marché");
        // }
    }
  
    // Effectue le déplacement vers l’arrière
    public function goBack($_currentAngle) {

        switch($_currentAngle) {
            case 90:
                --$this->_currentY;
            break;
            case 0:
                --$this->_currentX;
            break;
            case 270:
                ++$this->_currentY;
            break;
            case 180:
                ++$this->_currentX;
            break;
        }
    }

    // Effectue le déplacement vers la droite
    public function goRight($_currentAngle) {

        switch($_currentAngle) {
            case 90:
                ++$this->_currentX;
            break;
            case 0:
                --$this->_currentY;
            break;
            case 270:
                --$this->_currentX;
            break;
            case 180:
                ++$this->_currentY;
            break;
        }
    }

    // // Effectue le déplacement vers la gauche
    public function goLeft($_currentAngle) {

        switch($_currentAngle) {
            case 0:
                ++$this->_currentY;
            break;
            case 90:
                --$this->_currentX;
            break;
            case 180:
                ++$this->_currentX;
            break;
            case 270:
                --$this->_currentY;
            break;
        }
    }

    // Tourner sur la droite
    public function turnRight($_currentAngle) {

        switch($_currentAngle) {
            case 0:
                $this->_currentAngle = 270;
            break;
            case 90:
                $this->_currentAngle = 0;
            break;
            case 180:
                $this->_currentAngle = 90;
            break;
            case 270:
                $this->_currentAngle = 180;
            break;
        }
    }

    // Tourner sur la gauche
    public function turnLeft($_currentAngle) {

        switch($_currentAngle) {
            case 0:
                $this->_currentAngle = 90;
            break;
            case 90:
                $this->_currentAngle = 180;
            break;
            case 180:
                $this->_currentAngle = 270;
            break;
            case 270:
                $this->_currentAngle = 0;
            break;
        }
    }
}

?>