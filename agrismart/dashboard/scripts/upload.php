<?php
    if (isset($_FILES['popup-photo-input']) && $_FILES['popup-photo-input']['error'] != UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['popup-photo-input'];
        
        // Estraggo le informazioni dal file caricato
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileType = $file['type'];
        
        // Estraggo l'estensione del file
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Definisco le estensioni consentite
        $allowed = array('jpg', 'jpeg', 'png');
        
        // Controllo se il file ha un'estensione valida
        if (in_array($fileExt, $allowed)) {
            // Controllo se ci sono errori
            if ($fileError === 0) {
                // Controllo la dimensione del file (esempio: massimo 5MB)
                if ($fileSize < 5000000) {
                    // Creo un nuovo nome univoco per il file per evitare conflitti
                    $fileNewName = uniqid('', true) . "." . $fileExt;
                    
                    // Definisco la destinazione del file caricato
                    $fileDestination = './img-users/' . $fileNewName;
                    
                    // Sposto il file caricato dalla posizione temporanea alla destinazione
                    if (move_uploaded_file($fileTmpName, $fileDestination)) {
                        $error=0;
                    } else {
                        $error=1;
                    }
                } else {
                    $error=2;
                }
            } else {
                $error=1;
            }
        } else {
            $error=3;
        }
    } else {
        $error=4;
    }
?>