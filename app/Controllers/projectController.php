<?php

namespace App\Controllers;

use App\Models\Project;
use Respect\Validation\Validator as v;

class projectController extends baseController {
    public function projectAction($request){
        $responseMessage = null;

        //var_dump((string)$request->getBody());
        //var_dump($request->getParsedBody());

    if($request->getMethod() == 'POST'){
        $postData = $request->getParsedBody();
        $projectValidator = v::key('title', v::stringType()->notEmpty()) //key los mimbros de un arreglo para validar
                ->key('description', v::stringType()->notEmpty());

        try{
            $projectValidator->check($postData); //(validate) true o false  //(assert) otra forma de validar(excepciones)       
            $postData = $request->getParsedBody();

            $files = $request->getUploadedFiles();
            $logo = $files['logo'];

            if($logo->getError() == UPLOAD_ERR_OK) {
                $fileName = $logo->getClientFilename();
                $logo->moveTo("uploads/$fileName");
            }

            $project = new Project();
            $project->title = $postData['title'];
            $project->description = $postData['description'];
            $project->fileName = $fileName;
            $project->save();

            $responseMessage = 'Saved';
        } catch (\Exception $e){  //para capturar una excepcion (un mensaje) 
            $responseMessage = $e->getMessage();
        }
    }

            return $this->renderHTML('addProject.twig', [
                'responseMessage' => $responseMessage
            ]);

    }
}