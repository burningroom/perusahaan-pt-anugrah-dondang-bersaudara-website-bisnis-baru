<?php

namespace App\Services;

class SoapService
{
    public function sayHello($name)
    {
        return "Hello, {$name}!";
    }

    // You can add more methods here
}
