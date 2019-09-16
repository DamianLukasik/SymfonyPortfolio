<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
require_once __DIR__.'/../OnlyReadVariables.php';

class MachineLearningAlgorithmsController extends AbstractController
{
    private $title = "Provider";
    private $subtitle = "Algorytmy uczenia maszynowego";
    private $controller_name = 'MachineLearningAlgorithmsController';

    /**
     * @Route("/machineLearningAlgorithms", name="machineLearningAlgorithms")
     */
    public function index()
    {
        return $this->render('machine_learning_algorithms/index.html.twig', [
            "title" => $this->title,
            "subtitle" => $this->subtitle,
            'controller_name' => $this->controller_name,
            "icon"=>PATHS["machineLearningAlgorithms"]["icon"],
            'routes' => PATHS
        ]);
    }
}
