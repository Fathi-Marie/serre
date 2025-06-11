<?php

/**
 * Fonction échappant les caractères html dans $message
 * @param string $message chaîne à échapper
 * @return string chaîne échappée
 */
function e($message){

    return htmlspecialchars($message, ENT_QUOTES);
}

function afficherPopup($message) {
    if (isset($message) && !empty($message)) {
        echo '<script type="text/javascript">';
        echo 'alert("' . addslashes($message) . '");die();';
        echo '</script>';
    }
}


