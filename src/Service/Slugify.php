<?php

namespace App\Service;


class Slugify
{

    function generate(string $input) : string
    {
        $toURLChar = [
            'à' => 'a',
            'â' => 'a',
            'é' => 'e',
            'è' => 'e',
            'ê' => 'e',
            'ë' => 'e',
            'ù' => 'u',
            'ç' => 'c',
            ' ' => '-',
        ];
        $specialChar = ['...', '.', '!', '?', '/', ',', ';'];
        $input = trim(str_replace($specialChar, "", $input));
        return strtolower(strtr($input, $toURLChar));
    }
}