<?php

namespace App\Helpers;

class AlertHelper
{
    public static function success($message)
    {
        session()->flash('alert', [
            'type' => 'success',
            'message' => $message
        ]);
    }

    public static function error($message)
    {
        session()->flash('alert', [
            'type' => 'error', 
            'message' => $message
        ]);
    }

    public static function warning($message)
    {
        session()->flash('alert', [
            'type' => 'warning',
            'message' => $message
        ]);
    }

    public static function info($message)
    {
        session()->flash('alert', [
            'type' => 'info',
            'message' => $message
        ]);
    }
}