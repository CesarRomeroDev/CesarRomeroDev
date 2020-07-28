<?php

namespace App\Controllers;

use App\Models\user;
use Respect\Validation\Validator as v;

class usersController extends baseController {
    public function usersAction(){
        return $this->renderHTML('addUser.twig');
    }

    public function postSaveUser($request){
        $responseMessage = null;

        //var_dump((string)$request->getBody());
        //var_dump($request->getParsedBody());

        if($request->getMethod() == 'POST') { 
            $postData = $request->getParsedBody();
            $userValidator = v::key('email', v::stringType()->notEmpty()) //key los mimbros de un arreglo para validar
                  ->key('password', v::stringType()->notEmpty());

           try{
            $userValidator->check($postData); //(validate) true o false  //(assert) otra forma de validar (excepciones)
            $postData = $request->getParsedBody();
            $user = new user();
            $user->email = $postData['email'];
            $user->password = password_hash($postData['password'], PASSWORD_DEFAULT);
            $user->save();

            $responseMessage = 'Saved';
           }catch (\Exception $e){  //para capturar una excepcion (un mensaje) 
            $responseMessage = $e->getMessage();
            }
        }
    
    

        return $this->renderHTML('addUser.twig', [
            'responseMessage' => $responseMessage
        ]);
    }
}