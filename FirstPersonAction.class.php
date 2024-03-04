<?php

class FirstPersonAction extends BaseClass {

    // connexion à la base des données
    private $_dbh;
    
    // methode construct pour la connexion à la base
    public function __construct() {
        $this->_dbh = new DataBase;
    }

    // constante de classe indiquant le répertoire dans lequel sont placées les images
    const IMAGES_FOLDER = './images/';


    public function checkAction($X, $Y, $A, $statAction) {
        error_log("checkAction " .$X." ".$Y." ".$A. " " .$statAction);
        $action = "SELECT * FROM actions 
                    JOIN map ON map.id = actions.map_id 
                    WHERE coordx=:currentX AND coordy=:currentY AND direction=:currentAngle AND actions.status=:statusAction ";
        $stmt = $this->_dbh->prepare($action);
 
        $stmt->bindParam(':currentX', $X, PDO::PARAM_INT);
        $stmt->bindParam(':currentY', $Y, PDO::PARAM_INT);
        $stmt->bindParam(':currentAngle', $A, PDO::PARAM_INT);
        $stmt->bindParam(':statusAction', $statAction, PDO::PARAM_INT);
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        error_log("result check Action " .print_r($result, 1));
 
        if(!empty($result) && isset($result['map_id']) ) {
             return true;
        } else {
            error_log('Problem check action');
             return false;    
        }   
    }


    public function doAction($X, $Y, $A, $statAction) {
        error_log("do action:" .print_r($_POST, 1));    
        
        if(isset($_POST['action'])) {
            // change the status to abled/enabled the button action
            // 
            if($statAction === 0) {
                $statAction = 1;
            } else if ($statAction === 1) {
                $statAction = 0;
            } 

            $changeStat = "UPDATE actions SET status = :statAction ";
            $stmtc = $this->_dbh->prepare($changeStat);
            $stmtc->bindParam(':statAction', $statAction, PDO::PARAM_INT);
            if($stmtc->execute()) {
                error_log('status changé dans la table actions');
            } else {
                error_log('le status dans la table actions n\'a pas changé');
            }


            $takeOrUse = "SELECT images.*, actions.* FROM images
                            JOIN actions ON images.map_id = actions.map_id
                            JOIN map ON map.id = images.map_id 
                            WHERE coordx=:currentX AND coordy=:currentY AND direction=:currentAngle";

            $stmtT = $this->_dbh->prepare($takeOrUse);
            $stmtT->bindParam(':currentX', $X);
            $stmtT->bindParam(':currentY', $Y);
            $stmtT->bindParam(':currentAngle', $A);

            $stmtT->execute();
            $takeOrUseFinal = $stmtT->fetch(PDO::FETCH_ASSOC);
            error_log("take or use : " .print_r($takeOrUseFinal, 1));
            if($takeOrUseFinal !== false) {
                if (isset($takeOrUseFinal['map_id']) && isset($takeOrUseFinal['requis'])) {
                    if ($takeOrUseFinal['map_id'] === 14 && $takeOrUseFinal['requis'] === 0) {
                        return self::IMAGES_FOLDER . "12-90-1.jpg";
                    }
                    if ($takeOrUseFinal['map_id'] === 3 && $takeOrUseFinal['requis'] === 1 ) {    
                        return self::IMAGES_FOLDER . "01-180-1.jpg";
                    }
                } else {
                    error_log('map id et requis pas trouvées dans take or use ');
                }
            } else {
                error_log('take or use false');
            }  
        }
    } 
}

?>