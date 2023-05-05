<?php

session_start();

if (!empty($_REQUEST['contact']) && !empty($_REQUEST['name']) && !empty($_REQUEST['message'])) {
    $content = <<<EOF
    <html>
        <body>
            <p>Le nom : <strong>{$_REQUEST['name']}</strong></p>
            <p>Le contact : <strong>{$_REQUEST['contact']}</strong></p>
            <p>Le message : <strong>{$_REQUEST['message']}</strong></p>
        </body>
    </html>
    EOF;

    $_SESSION['message_sent'] = true;

    mail('contact@nuances-facade.fr', '🎉 Nouveau message de client', $content, "Content-Type: text/html; charset=UTF-8\r\n");
    header('Location: /');
} else {
    echo '<a href="javascript:history.back()">Le formulaire est mal rempli, re-essayez</a>';
}
