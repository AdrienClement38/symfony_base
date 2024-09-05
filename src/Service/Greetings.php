<?php

namespace App\Service;

class Greetings
{
    public function greet(string $name): string
    {
        return "Hello, $name!";
    }

    public function bye(string $name): string
    {
        return "Goodbye, $name!";
    }
}
