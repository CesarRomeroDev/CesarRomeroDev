<?php

namespace App\Controllers;

use App\Models\job;
use Respect\Validation\Validator as v;

class jobsController extends baseController {
    public function jobsAction($request){
        $responseMessage = null;

        //var_dump((string)$request->getBody());
        //var_dump($request->getParsedBody());

        if($request->getMethod() == 'POST') { 
            $postData = $request->getParsedBody();
            $jobValidator = v::key('title', v::stringType()->notEmpty()) //key los mimbros de un arreglo para validar
                  ->key('description', v::stringType()->notEmpty());

           try{
            $jobValidator->check($postData); //(validate) true o false  //(assert) otra forma de validar (excepciones)
            $postData = $request->getParsedBody();

            $file = $request->getUploadedFiles();
            $logo = $file['logo'];

            if($logo->getError() == UPLOAD_ERR_OK){
                $fileName = $logo->getClientFilename();
                $logo->moveTo("uploads/$fileName");
            }
            $job = new job();
            $job->title = $postData['title'];
            $job->description = $postData['description'];
            $job->fileName = $fileName;
            $job->save();

            $responseMessage = 'Saved';
           }catch (\Exception $e){  //para capturar una excepcion (un mensaje) 
            $responseMessage = $e->getMessage();
            }
        }
    
    

        return $this->renderHTML('addJob.twig', [
            'responseMessage' => $responseMessage
        ]);
    }
}