<?php

namespace App\Controllers;

use Laminas\Diactoros\Response\HtmlResponse as ResponseHtmlResponse;
use \Twig_Loader_Filesystem;
use \Zend\Diactoros\Response\HtmlResponse;

class baseController{
    protected $templateEnguine;

    public function __construct(){
        $loader = new \Twig\Loader\FilesystemLoader('../views');
        $this->templateEnguine = new \Twig\Environment($loader, [
            'debug' => true,      //debug no permite ver si tenemos errores 
            'cache' => false,

        ]);
    }

    public function renderHTML($fileName, $data = []){
        return new ResponseHtmlResponse( $this->templateEnguine->render($fileName, $data ));
    }
}