<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class language {

    public $data;

    function __construct($language) {
        $data = file_get_contents("externals/" . $language . "/" . $language . ".json");
        $this->data = json_decode($data);
    }

    function translate() {
        return $this->data;
    }

}

$browserLanguage = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
$language = new language($browserLanguage);
$lang = $language->translate();