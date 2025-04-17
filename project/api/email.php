

<?php

$to      = 'kocsiskornel800@gmail.com';
$subject = 'the subject';
$message = 'hello';
$headers = 'From: kovijani24735@gmail.com' . "\r\n" .
    'Reply-To: webmaster@example.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);

?>

