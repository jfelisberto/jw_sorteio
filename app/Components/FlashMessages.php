<?php

namespace App\Components;

trait FlashMessages
{

    /**
     * Usage in Contoller
     * self::message('danger', 'An unknown error occured.');
     * self::message('info', 'Just a plain message.');
     * self::message('success', 'Item has been added.');
     * self::message('warning', 'Service is currently under maintenance.');
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $level
     * @param  string  $message
     * @param  string  $title
     * @return array [level, icon, message]
     */
    protected static function message($level = 'info', $message = null, $title = null)
    {
        if (session()->has('messages')) {
            $messages = session()->pull('messages');
        }

        if (empty($title)) {
            switch ($level) {
                case 'success':
                  $title = 'Sucesso';
                  break;
                case 'danger':
                  $title = 'Erro';
                  break;
                case 'warning':
                  $title = 'Cuidado';
                  break;
                default:
                  $title = 'Informação';
                  break;
            }
        }

        $messages[] = $message = ['level' => $level, 'title' => $title, 'message' => $message];

        session()->flash('messages', $messages);

        return $message;
    }

    protected static function messages()
    {
        return self::hasMessages() ? session()->pull('messages') : [];
    }

    protected static function hasMessages()
    {
        return session()->has('messages');
    }

    protected static function success($message)
    {
        return self::message('success', $message);
    }

    protected static function info($message)
    {
        return self::message('info', $message);
    }

    protected static function warning($message)
    {
        return self::message('warning', $message);
    }

    protected static function danger($message)
    {
        return self::message('danger', $message);
    }
}
