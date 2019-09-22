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
    private $quotations = array(
        ['author'=>'Alan Turing','text'=>'Komputer zasłuży na miano inteligentnego, jeżeli spowoduje u człowieka przekonanie, że jest człowiekiem.'],
        ['author'=>'Alan Turing','text'=>'"Czy maszyny mogą myśleć?" ... Nową formę problemu można opisać w kategoriach gry, którą nazywamy "grą imitacji".'],
        ['author'=>'Alan Turing','text'=>'Czy maszyny nie mogą dokonywać czegoś, co powinno być opisane jako myślenie, ale co jest bardzo różne od tego, co robi człowiek?'],
        ['author'=>'Roy Batty','text'=>'Widziałem rzeczy, którym wy ludzie nie dalibyście wiary. Statki szturmowe w ogniu sunące nieopodal ramion Oriona. Oglądałem promieniowanie skrzące się w ciemnościach blisko wrót Tannhausera. Wszystkie te chwile zostaną stracone w czasie jak łzy w deszczu. Pora umierać.'],
        ['author'=>'Albert Einstein','text'=>'Wyobraźnia bez wiedzy może stworzyć rzeczy piękne. Wiedza bez wyobraźni najwyżej doskonałe.'],
        ['author'=>'Elon Musk','text'=>'Sztuczna inteligencja jest potencjalnie groźniejsza od bomb atomowych.'],
        ['author'=>'Jen Hsun Huang','text'=>'Na naszych oczach startuje czwarta rewolucja przemysłowa, uczące się komputery drastycznie zmienią nasze życie. Bardziej niż koło, prąd czy internet.'],
        ['author'=>'Gary Marcus','text'=>'Każdy, kto pracuje nad sztuczną inteligencją, wie, że komputery pokonają nas we wszystkim.'],
        ['author'=>'Terminator','text'=>'Wrócę tu'],
    );

    private function randomQuotations(){
        $num = rand(0,count($this->quotations)-1);
        return $this->quotations[$num];
    }

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
            'routes' => PATHS,
            'quotation' => $this->randomQuotations()
        ]);
    }
}
