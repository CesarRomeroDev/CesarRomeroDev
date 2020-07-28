<?php

namespace App\Controllers;

use App\Models\user;
use Laminas\Diactoros\Response\RedirectResponse as ResponseRedirectResponse;
use Respect\Validation\Validator as v;
use \Zend\Diactoros\Response\RedirectResponse;

class AuthController extends baseController {
    public function getLogin(){
        return $this->renderHTML('login.twig');
    }

    public function postLogin($request){
        $responseMessage = null;

        //var_dump((string)$request->getBody());
        //var_dump($request->getParsedBody());

        if($request->getMethod() == 'POST') { 
            $postData = $request->getParsedBody();
            $loginValidator = v::key('email', v::stringType()->notEmpty()) //key los mimbros de un arreglo para validar
                  ->key('password', v::stringType()->notEmpty());

           try{
            $loginValidator->check($postData); //(validate) true o false  //(assert) otra forma de validar (excepciones)
            $postData = $request->getParsedBody();
            $user = new user();
            $user = User::where('email', $postData['email'])->first();
        if($user){
            if(password_verify($postData['password'], $user->password)) {  //password_verify (verificar password)
                $_SESSION['userId'] = $user->id;
                return new RedirectResponse('admin');
            } else{
                $responseMessage = 'Bad Credentials';
            }
        }else{
            $responseMessage = 'Bad Credentials';
        }
            $user->save();

           }catch (\Exception $e){  //para capturar una excepcion (un mensaje) 
            $responseMessage = $e->getMessage();
            }
        }
    
    

        return $this->renderHTML('login.twig', [
            'responseMessage' => $responseMessage
        ]);
    }

    public function getLogout() {
        unset($_SESSION['userId']);
                return new RedirectResponse('login');
    }
}