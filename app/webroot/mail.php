<?php
register_shutdown_function('__fatalHandler');

  function __fatalHandler()
  {
      $error   = error_get_last();
      echo '<pre>'; print_r($error);
   }
   

$to      = 'anil.vadhavane@gmail.com'; 
$subject = 'the subject'; 
$message = 'hello'; 
$headers = 'From: webmaster@example.com' . "\r\n" . 
    'Reply-To: webmaster@example.com' . "\r\n" . 
    'X-Mailer: PHP/' . phpversion(); 

if(mail($to, $subject, $message, $headers)) {
 echo 'sent';   
} else {
    echo 'not';
}
?>