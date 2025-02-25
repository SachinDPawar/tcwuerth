<?php

class JWT
{
    public $key;

    public function init()
    {
        require dirname(__FILE__) . '/src/JWT.php';
        require dirname(__FILE__) . '/src/BeforeValidException.php';
        require dirname(__FILE__) . '/src/ExpiredException.php';
        require dirname(__FILE__) . '/src/SignatureInvalidException.php';

    }

    public function encode($payload)
    {
        return \Firebase\JWT\JWT::encode($payload, $this->key);
    }

    public function decode($msg)
    {
        return \Firebase\JWT\JWT::decode($msg, $this->key, array('HS256'));
    }
}