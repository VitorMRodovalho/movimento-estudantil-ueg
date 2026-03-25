<?php
// $Id: ptbrasil.php, v1.0 01/07/2006 pdg Exp $
//
defined( '_VALID_MOS' ) or die( 'O acesso a este componente não é permitido.' );

DEFINE('_EMAIL_CONTENT', 'Caro {name},

You have been sent a virtual postcard. To pick your card, simply click on the link below:

{link}

Mural do TCC.

Para visualizar o mural do TCC visite:
{website}

Consideração amável,
The Staff');
DEFINE('_POSTCARD_TITLE', 'Enviar TCC.');
DEFINE('_POSTCARD_CONTINUE', 'Continuar');
DEFINE('_POSTCARD_BACK', 'Voltar');
DEFINE('_POSTCARD_SEND', 'Enviar');
DEFINE('_POSTCARD_YOUR_NAME', 'Seu Nome');
DEFINE('_POSTCARD_YOUR_EMAIL', 'Seu e-mail');
DEFINE('_POSTCARD_RECIPIENT_NAME', 'Recipient Nome');
DEFINE('_POSTCARD_RECIPIEN_EMAIL', 'Recipient\'s e-mail address');
DEFINE('_POSTCARD_SUBJECT', 'Subject:');
DEFINE('_POSTCARD_MESSAGE', 'Your message:');
DEFINE('_POSTCARD_SEND_MSG', 'O TCC foi enviado <b>{name}</b> ao {mail} has been sent.<br><br>The Staff');
DEFINE('_POSTCARD_PREVIEW_TO', 'Para:');
DEFINE('_POSTCARD_PREVIEW_FROM', 'De:');
DEFINE('_POSTCARD_MAIL_SUBJECT', 'Objetivo: TCC');
DEFINE('_POSTCARD_VIEW', 'Seu TCC.');
DEFINE('_POSTCARD_VIEW_FOOTER', 'This postcard may not be used for any commercial purpose.<br>We are not responsible for the contents of this postcard.');
DEFINE('_POSTCARD_VALID_1', 'Você deve introduzir seu nome.');
DEFINE('_POSTCARD_VALID_2', 'Você deve introduzir seu e-mail.');
DEFINE('_POSTCARD_VALID_3', 'Você deve introduzir nome recipient\'s.');
DEFINE('_POSTCARD_VALID_4', 'Você deve introduzir e-mail recipient\'s .');
DEFINE('_POSTCARD_VALID_5', 'Você deve introduzir o objetivo.');
DEFINE('_POSTCARD_VALID_6', 'Você deve introduzir uma mensagem.');
?>