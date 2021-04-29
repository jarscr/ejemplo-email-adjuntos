<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf();

include('plantillas/factura.php');


    //$mpdf->SetWatermarkText('DOCUMENTO EN AMBIENTE DE PRUEBAS');
    //$mpdf->showWatermarkText = true;
    $body = mb_convert_encoding($body, 'UTF-8', 'UTF-8');
    $mpdf->SetFooter($footer);
    $mpdf->WriteHTML($body);
    $content = $mpdf->Output('', 'S');



    $mail = new PHPMailer(true);
    try {
      // Passing `true` enables exceptions
      $mail->SMTPDebug = 0;                               // Enable verbose debug output
      $mail->smtpConnect([
        'ssl' => [
          'verify_peer' => false,
          'verify_peer_name' => false,
          'allow_self_signed' => true
        ]
      ]);                                   // Set mailer to use SMTP
      $mail->Host = 'mi.servidor.com';                  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                             // Enable SMTP authentication
      $mail->Username = 'demo@servidor.com';                 // SMTP username
      $mail->Password = 'p@ssword';                      // SMTP password
      $mail->SMTPSecure = 'ssl';                          // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 465;                                  // TCP port to connect to
      $mail->CharSet = 'UTF-8';
      //Recipients
      $mail->setFrom('noreply@servidor.com','Servidor');

      $mail->addReplyTo('micorreo@servidor.com', 'Servidor');
      //$mail->addAddress('ventas@mktechstore.com');
     
        $mail->addAddress('email@cliente.com');
      


      //Attachments
      $variable = file_get_contents('xml/ejemplo_FacturaElectronica.xml');
      $mail->AddStringAttachment($variable, $Factura['claveHacienda'] . ".xml", "base64", "application/xml");
      $mail->AddStringAttachment($content,  "factura.pdf", "base64", "application/pdf");

      //Content
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'Factura No 00100001010000000027';
      $mail->Body    = '<p>Estimado Cliente: <b>Juan Perez</b></p><p>Adjunto a este correo encontrará un Comprobante Electrónico en formato XML y su correspondiente representación en formato PDF. </p>'.$body;
      $mail->AltBody = 'Estimado Cliente: Juan Perez. Adjunto a este correo encontrará un Comprobante Electrónico en formato XML y su correspondiente representación en formato PDF. ';



      $mail->send();

      echo json_encode(array('Enviado' => $Factura['claveHacienda']));
      //return true;
    } catch (Exception $e) {


      echo '<br>Error Enviando Email ';
    }
  