<?php

namespace MyProject\Services;

use MyProject\Models\Users\User;

class EmailSender
{

    public static function send(
        User $receiver,
        string $subject,
        string $templateName,
        array $templateVars = []
    ): void {
        extract($templateVars);

        ob_start();
        require __DIR__ . '/../../../templates/mail/' . $templateName;
        $body = ob_get_contents();
        ob_get_clean();

        mail($receiver->getEmail(), $subject, $body, 'Content-Type: text/hmtl; charset\UTF-8');
    }
}

?>
