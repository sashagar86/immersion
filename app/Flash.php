<?php

namespace App;

class Flash
{
    public static function setMessage($message, $type = 'error')
    {
        $_SESSION['messages']['text'][] = $message;
        $_SESSION['messages']['label'] = $type;
    }

    public static function getMessages()
    {
        if ($messages = $_SESSION['messages']['text']) {
            $messages = implode('<br/>', $messages);
            $label = $_SESSION['messages']['label'];
            unset($_SESSION['messages']);
            return '<div class="alert alert-' . $label . ' text-dark" role="alert">
                    ' . $messages . '
                </div>';
        }
        return false;
    }
}