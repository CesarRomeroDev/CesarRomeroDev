<?php
namespace App\Controllers;



class adminController extends baseController {
    public function getIndex(){
        return $this->renderHTML('admin.twig');
    }
}