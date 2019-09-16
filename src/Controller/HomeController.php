<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
require_once __DIR__.'/../OnlyReadVariables.php';

class HomeController extends AbstractController 
{
    private $title = "Provider";
    private $subtitle = "Galeria";
    private $controller_name = 'HomeController';

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            "title" => $this->title,
            "subtitle" => $this->subtitle,
            'controller_name' => $this->controller_name,
            "icon"=>PATHS["home"]["icon"],
            'routes' => PATHS
        ]);
    }
}
