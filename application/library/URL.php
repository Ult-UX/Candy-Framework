<?php
namespace App\library;

use Candy\core\URI;

class URL
{
    private $URI;
    public function __construct()
    {
        $this->URI = new URI();
    }
}
