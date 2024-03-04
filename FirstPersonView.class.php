<?php

class FirstPersonView extends BaseClass {

    // connexion à la base des données
    private $_dbh;
    
    // methode construct pour la connexion à la base
    public function __construct() {
        $this->_dbh = new DataBase;
    }
    
    // constante de classe indiquant le répertoire dans lequel sont placées les images
    const IMAGES_FOLDER = './images/';


    // Methode pour renvoier le chemin vers le fichier image à afficher
    public function getView($X, $Y, $A, $Action) {

        error_log($X." ".$Y." ".$A. "" .$Action);
       $img = "SELECT * FROM images 
                JOIN map ON map.id = images.map_id 
                WHERE coordx=:currentX AND coordy=:currentY AND direction=:currentAngle AND images.status_action =:statusAction";
       $stmt = $this->_dbh->prepare($img);

       $stmt->bindParam('currentX', $X, PDO::PARAM_INT);
       $stmt->bindParam('currentY', $Y, PDO::PARAM_INT);
       $stmt->bindParam('currentAngle', $A, PDO::PARAM_INT);
       $stmt->bindParam(':statusAction', $Action, PDO::PARAM_INT);
       $stmt->execute();
       $result = $stmt->fetch(PDO::FETCH_ASSOC);
       error_log("result getview " .print_r($result, 1));

       if(!empty($result) && isset($result['path'])) {
            return self::IMAGES_FOLDER . $result['path'];
       } else {
            return self::IMAGES_FOLDER . 'default.jpg';
            error_log('Problem get view');
       }
    }

    // Methode pour envoier la direction vers laquelle pointe la boussole
    public function getAnimCompass($A) {
        switch($A) {
            case 0:
                return "rotate(0deg)";
            break;
            case 90:
                return "rotate(90deg)";
            break;
            case 180:
                return "rotate(180deg)";
            break;
            case 270:
                return "rotate(270deg)";
            break;
            
            default:
                return "rotate(0deg)";
            break;
        }
    }
}

?>