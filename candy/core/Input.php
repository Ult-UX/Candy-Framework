<?php
namespace Candy\core;

final class Input
{
    public function method($method = null)
    {
        if (!$method) {
            return strtolower($_SERVER['REQUEST_METHOD']);
        }
        if ($method == strtolower($_SERVER['REQUEST_METHOD'])) {
            return true;
        }
    }
    public function post($name)
    {
        if (empty($_POST[$name])) {
            return;
        }
        return $_POST[$name];
    }
}
