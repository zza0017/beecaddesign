<?php
$to = "zabiholllah@gmail.com";  // Your email address
$subject = "New Quote Request from Your Website";

$name = htmlspecialchars($_POST["name"]);
$email = htmlspecialchars($_POST["email"]);
$message = htmlspecialchars($_POST["message"]);

$body = "You received a new quote request from your website.\n\n";
$body .= "Name: $name\nEmail: $email\n\nMessage:\n$message";

// Handle file attachment
if(isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_name = $_FILES['file']['name'];
    $file_type = $_FILES['file']['type'];
    $content = chunk_split(base64_encode(file_get_contents($file_tmp)));

    $separator = md5(time());
    $eol = "\r\n";

    $headers = "From: $email" . $eol;
    $headers .= "MIME-Version: 1.0" . $eol;
    $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;

    $body_message = "--" . $separator . $eol;
    $body_message .= "Content-Type: text/plain; charset=\"utf-8\"" . $eol;
    $body_message .= "Content-Transfer-Encoding: 7bit" . $eol . $eol;
    $body_message .= $body . $eol;

    $body_message .= "--" . $separator . $eol;
    $body_message .= "Content-Type: " . $file_type . "; name=\"" . $file_name . "\"" . $eol;
    $body_message .= "Content-Transfer-Encoding: base64" . $eol;
    $body_message .= "Content-Disposition: attachment" . $eol . $eol;
    $body_message .= $content . $eol;
    $body_message .= "--" . $separator . "--";

    if (mail($to, $subject, $body_message, $headers)) {
        echo "Your request has been sent successfully.";
    } else {
        echo "There was an error sending your request.";
    }
} else {
    echo "File upload failed or not included.";
}
?>
