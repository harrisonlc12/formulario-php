<?php 

     
    function form_mail($sPara, $sAsunto, $sTexto, $sDe) 
    { 
     
        $bHayFicheros = 0; 
        $sCabeceraTexto = ""; 
        $sAdjuntos = ""; 
        $sCuerpo = $sTexto; 
        $sSeparador = uniqid("_Separador-de-datos_"); 
         
        $sCabeceras = "MIME-version: 1.0\n"; 
         
        // Recogemos los campos del formulario 
        foreach ($_POST as $sNombre => $sValor) 
            $sCuerpo = $sCuerpo."\n".$sNombre." = ".$sValor; 
             
        // Recorremos los Ficheros 
        foreach ($_FILES as $vAdjunto) 
        { 
             
            if ($bHayFicheros == 0) 
            { 
                 
                // Hay ficheros 
                 
                $bHayFicheros = 1; 
                 
                // Cabeceras generales del mail 
                $sCabeceras .= "Content-type: multipart/mixed;"; 
                $sCabeceras .= "boundary=\"".$sSeparador."\"\n"; 
                 
                // Cabeceras del texto 
                $sCabeceraTexto = "--".$sSeparador."\n"; 
                $sCabeceraTexto .= "Content-type: text/plain;charset=iso-8859-1\n"; 
                $sCabeceraTexto .= "Content-transfer-encoding: 7BIT\n\n"; 
                 
                $sCuerpo = $sCabeceraTexto.$sCuerpo; 
                 
            } 
             
            // Se añade el fichero 
            if ($vAdjunto["size"] > 0) 
            { 
                $sAdjuntos .= "\n\n--".$sSeparador."\n"; 
                $sAdjuntos .= "Content-type: ".$vAdjunto["type"].";name=\"".$vAdjunto["name"]."\"\n"; 
                $sAdjuntos .= "Content-Transfer-Encoding: BASE64\n"; 
                $sAdjuntos .= "Content-disposition: attachment;filename=\"".$vAdjunto["name"]."\"\n\n";                  
                 
                $oFichero = fopen($vAdjunto["tmp_name"], 'rb'); 
                $sContenido = fread($oFichero, filesize($vAdjunto["tmp_name"])); 
                $sAdjuntos .= chunk_split(base64_encode($sContenido)); 
                fclose($oFichero); 
            } 
             
        } 
         
        // Si hay ficheros se añaden al cuerpo 
        if ($bHayFicheros) 
            $sCuerpo .= $sAdjuntos."\n\n--".$sSeparador."--\n"; 
         
        // Se añade la cabecera de destinatario 
        if ($sDe)$sCabeceras .= "From:".$sDe."\n"; 
         
        // Por último se envia el mail 
        return(mail($sPara, $sAsunto, $sCuerpo, $sCabeceras)); 
    } 
         
        //Ejemplo de como usar: 
       //  if (form_mail("ingrese aqui el email del destinatario", "ingrese el asunto", 
        //                             "Los datos introducidos en el formulario son:\n", 
        //                             "ingrese el email del remitente")) 
       //  echo "Su formulario ha sido enviado con exito"; 
         
        if (form_mail("harrisonlc12347@gmail.com", "Formulario de Inscripción", 
                                "Los datos introducidos en el formulario son:\n", 
                         "harrisonlc123478@gmail.com")) 
  echo "Su formulario ha sido enviado con exito"; 
        

        
         

?>