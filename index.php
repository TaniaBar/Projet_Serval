<?php
error_log('start');
session_start();


function loadClass($class) {
    require $class . ".class.php";
}

spl_autoload_register("loadClass");

// lier le fichier database
$_dbh = new DataBase();

// lier le fichier baseclass
$baseClass = new BaseClass();
// error_log(print_r($baseClass, 1));
error_log("POST :".print_r($_POST, 1));

// lier le fichier firstpersonview
$FirstPersonView = new FirstPersonView();
//error_log(print_r($FirstPersonView, 1));

// lier le fichier firstpersontext
$FirstPersonText = new FirstPersonText();
error_log(print_r($FirstPersonText, 1));

// lier le fichier firstpersonaction
$FirstPersonAction = new FirstPersonAction();
error_log("first person action " . print_r($FirstPersonAction, 1));

// si aucun bouton n'a été cliqué, les coordonées de depart sont celles-ci
if(empty($_POST)) {

    $baseClass->setCurrentX(0);
    $baseClass->setCurrentY(1);
    $baseClass->setCurrentAngle(0);
    $baseClass->setStatusAction(0);

    $FirstPersonView->setCurrentX(0);
    $FirstPersonView->setCurrentY(1);
    $FirstPersonView->setCurrentAngle(0);
    $FirstPersonView->setStatusAction(0);

    $FirstPersonAction->setCurrentX(0);
    $FirstPersonAction->setCurrentY(1);
    $FirstPersonAction->setCurrentAngle(0);
    $FirstPersonAction->setStatusAction(0);

    error_log('point de depart');

    // si un bouton a été cliqué, sauve les nouvelles coordonées et effectue le deplacement
} else {

    $baseClass->setCurrentX($_POST['currentX']);
    $baseClass->setCurrentY($_POST['currentY']);
    $baseClass->setCurrentAngle($_POST['currentAngle']);
    $baseClass->setStatusAction($_POST['statusAction']);

    $FirstPersonView->setCurrentX($_POST['currentX']);
    $FirstPersonView->setCurrentY($_POST['currentY']);
    $FirstPersonView->setCurrentAngle($_POST['currentAngle']);
    $FirstPersonView->setStatusAction($_POST['statusAction']);

    $FirstPersonAction->setCurrentX($_POST['currentX']);
    $FirstPersonAction->setCurrentY($_POST['currentY']);
    $FirstPersonAction->setCurrentAngle($_POST['currentAngle']);
    $FirstPersonAction->setStatusAction($_POST['statusAction']);
    
    // error_log(print_r($_POST, 1));
    if(isset($_POST['lookLeft'])) {
        $baseClass->turnLeft($baseClass->getCurrentAngle());   
    }
    
    if(isset($_POST['goForward'])) {
        $baseClass->goForward($baseClass->getCurrentAngle());   
    }
    
    if(isset($_POST['lookRight'] )) {
        $baseClass->turnRight($baseClass->getCurrentAngle());     
    }
    
    if(isset($_POST['goLeft'])) {
        $baseClass->goLeft($baseClass->getCurrentAngle());   
    }
    
    if(isset($_POST['goRight'])) {
        $baseClass->goRight($baseClass->getCurrentAngle());   
    }
    
    if(isset($_POST['goBack'])) {
        $baseClass->goBack($baseClass->getCurrentAngle());   
    }

    if(isset($_POST['action'])) {
       $FirstPersonAction->doAction($FirstPersonAction->getCurrentX(), $FirstPersonAction->getCurrentY(), $FirstPersonAction->getCurrentAngle(), $FirstPersonAction->getStatusAction());
       $FirstPersonAction->setStatusAction(1);
       $FirstPersonText->setStatusAction(1);
    }
}

if ($FirstPersonAction->getStatusAction() === 0) {
    $imagePath = $FirstPersonView->getView($baseClass->getCurrentX(), $baseClass->getCurrentY(), $baseClass->getCurrentAngle(), $baseClass->getStatusAction());
    $textPath = $FirstPersonText->getText($baseClass->getCurrentX(), $baseClass->getCurrentY(), $baseClass->getCurrentAngle(), $baseClass->getStatusAction());
} else {
    $imagePath = $FirstPersonAction->doAction($FirstPersonAction->getCurrentX(), $FirstPersonAction->getCurrentY(), $FirstPersonAction->getCurrentAngle(), $FirstPersonAction->getStatusAction());
    $textPath = $FirstPersonText->getText($FirstPersonAction->getCurrentX(), $FirstPersonAction->getCurrentY(), $FirstPersonAction->getCurrentAngle(), $FirstPersonAction->getStatusAction());
}

// error_log("baseX : ".print_r($baseClass->getCurrentX(), 1));
// error_log("baseY : ".print_r($baseClass->getCurrentY(), 1));
// error_log("baseA : ".print_r($baseClass->getCurrentAngle(), 1));

// pour afficher la bonne image on reprend les bonnes coordonées
// $imagePath = $FirstPersonView->getView($baseClass->getCurrentX(), $baseClass->getCurrentY(), $baseClass->getCurrentAngle(), $baseClass->getStatusAction());

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProjetServal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="up">
            <img src="<?php echo $imagePath; ?>" alt="">
        </div>
        <div class="down">
            <div class="down-gauche">
                <div class="direction">
                    <form action="index.php" method="POST">
                        <div class="first-line">
                                
                            <button type="submit" name="lookLeft" value="<?php echo $baseClass->getCurrentX(), $baseClass->getCurrentY(), $baseClass->getCurrentAngle(), $baseClass->getStatusAction()?>" 
                                <?php if (!$baseClass->checkTurnLeft($baseClass->getCurrentX(), $baseClass->getCurrentY(), $baseClass->getCurrentAngle(), $baseClass->getStatusAction())) echo "disabled"; ?>>
                                \
                            </button>
                                
                            <button type="submit" name="goForward" value="<?php echo $baseClass->getCurrentX(), $baseClass->getCurrentY(), $baseClass->getCurrentAngle(), $baseClass->getStatusAction()?>" 
                                <?php if (!$baseClass->checkForward($baseClass->getCurrentX(), $baseClass->getCurrentY(), $baseClass->getCurrentAngle(), $baseClass->getStatusAction())) echo "disabled"; ?>>
                                ↑
                            </button>
                                
                            <button type="submit" name="lookRight" value="<?php echo $baseClass->getCurrentX(), $baseClass->getCurrentY(), $baseClass->getCurrentAngle(), $baseClass->getStatusAction()?>" 
                                <?php if (!$baseClass->checkTurnRight($baseClass->getCurrentX(), $baseClass->getCurrentY(), $baseClass->getCurrentAngle(), $baseClass->getStatusAction())) echo "disabled"; ?>>
                                /
                            </button>
                                
                        </div>
                        <div class="second-line">
                                
                            <button type="submit" name="goLeft" value="<?php echo $baseClass->getCurrentX(), $baseClass->getCurrentY(), $baseClass->getCurrentAngle(), $baseClass->getStatusAction()?>" 
                                <?php if (!$baseClass->checkLeft($baseClass->getCurrentX(), $baseClass->getCurrentY(), $baseClass->getCurrentAngle(), $baseClass->getStatusAction())) echo "disabled"; ?>>
                                ←
                            </button>
                                
                            <button type="submit" name="action" 
                                    <?php if (!$FirstPersonAction->checkAction($baseClass->getCurrentX(), $baseClass->getCurrentY(), $baseClass->getCurrentAngle(), $baseClass->getStatusAction())) echo "disabled"; ?>>
                                X
                            </button>
                                
                            <button type="submit" name="goRight" value="<?php echo $baseClass->getCurrentX(), $baseClass->getCurrentY(), $baseClass->getCurrentAngle(), $baseClass->getStatusAction()?>" 
                                <?php if (!$baseClass->checkRight($baseClass->getCurrentX(), $baseClass->getCurrentY(), $baseClass->getCurrentAngle(), $baseClass->getStatusAction())) echo "disabled"; ?>>
                                →
                            </button>
                                
                        </div>
                        <div class="third-line">
                                
                            <button type="submit" name="goBack" value="<?php echo $baseClass->getCurrentX(), $baseClass->getCurrentY(), $baseClass->getCurrentAngle(), $baseClass->getStatusAction()?>" 
                                <?php if (!$baseClass->checkBack($baseClass->getCurrentX(), $baseClass->getCurrentY(), $baseClass->getCurrentAngle(), $baseClass->getStatusAction())) echo "disabled"; ?>>
                                ↓
                            </button>
                       
                        </div>

                        <input type="hidden" name="currentX" value="<?php echo $baseClass->getCurrentX() ?>">
                        <input type="hidden" name="currentY" value="<?php echo $baseClass->getCurrentY() ?>">
                        <input type="hidden" name="currentAngle" value="<?php echo $baseClass->getCurrentAngle() ?>">
                        <input type="hidden" name="statusAction" value="<?php echo $baseClass->getStatusAction() ?>">

                    </form>
                </div>

                <!-- affichage de la boussole selon les bonnes coordonées -->
                <div class="boussole" style="transform: <?php echo $FirstPersonView->getAnimCompass($baseClass->getCurrentAngle()) ?>">
                    <img src="assets/compass.png" >
                </div>
            </div>

            <!-- affichage du text selon les bonnes coordonées -->
            <div class="down-droit">
                <div class="ddspan">
                    <span>
                        <!-- < echo $FirstPersonText->getText($baseClass->getCurrentX(), $baseClass->getCurrentY(), $baseClass->getCurrentAngle(), $baseClass->getStatusAction()) ?> -->
                        <?php echo $textPath; ?>
                    </span>
                
                </div>
            </div>
        </div>
    </div>
</body>
</html>