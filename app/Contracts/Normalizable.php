<?php

namespace App\Contracts;

interface Normalizable
{
    public static function normalize($data): string;
}