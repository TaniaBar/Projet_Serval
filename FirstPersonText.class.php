<?php

class FirstPersonText extends BaseClass {

    // connexion à la base des données
    private $_dbh;
    
    // methode construct pour la connexion à la base
    public function __construct() {
        $this->_dbh = new DataBase;
    }

    // Methode pour renvoier le text à afficher
    public function getText($X, $Y, $A, $statusAction) {

       $text = "SELECT text FROM text 
                JOIN map ON map.id = text.map_id 
                WHERE coordx=:currentX AND coordy=:currentY AND direction=:currentAngle AND text.status_action =:statusAction";
                
       $stmt = $this->_dbh->prepare($text);
       $stmt->bindParam('currentX', $X, PDO::PARAM_INT);
       $stmt->bindParam('currentY', $Y, PDO::PARAM_INT);
       $stmt->bindParam('currentAngle', $A, PDO::PARAM_INT);
       $stmt->bindParam('statusAction', $statusAction, PDO::PARAM_INT);
       
       $stmt->execute();
       $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
       error_log("resultat text: " .print_r($resultat, 1));

       if(!empty($resultat) && isset($resultat['text'])) {
            return $resultat['text'];
       } else {   
            error_log('Problem get text');
       }
    }
}

?>