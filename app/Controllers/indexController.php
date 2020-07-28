<?php
namespace App\Controllers;

use App\Models\{job, Project};

class indexController extends baseController {
    public function indexAction(){

        //jobs.php
        $jobs = job::all();
        $projects = Project::all();

        //index.php vistas
        $lastName = 'Romero Esquivel';
        $name = "Julio Cesar $lastName";
        $limitMonths = 2000;

        return $this->renderHTML('index.twig', [
            'name' => $name,
            'jobs' => $jobs,
            'projects' => $projects
        ]);
    }
}