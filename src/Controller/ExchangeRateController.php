<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ExchangeRateController extends AbstractController
{
    private $description = "Poniższa tabela przedstawia akutalny kurs walut";
    private $title = "Provider";
    private $subtitle = "Kurs walut";
    private $page_name = "Aktualny kurs walut";
    private $controller_name = 'ExchangeRateController';
    private $base_url = 'http://api.nbp.pl/api/exchangerates/';
    private $content = [ "rates" => null, "columns" => null ];

    private $currency = [
        "€"=>"rates/A/EUR",
        "$"=>"rates/A/USD",
        "¥"=>"rates/A/JPY",
        "Fr"=>"rates/A/CHF",
        "£"=>"rates/A/GBP",
    ];

    /**
     * @Route("/exchangeRates", name="exchangeRates")
     */
    public function index()
    {
        if($this->getAllRatesOfCurrency($this->content["rates"])){
            $this->getSelectedData();
            if(count($this->content["rates"])!=0){
                $this->content["columns"] = array_keys($this->content["rates"][0]);
            }
        }
        //var_dump($this->content);die();
        return $this->render('exchange_rate/index.html.twig', [
            "title" => $this->title,
            "subtitle" => $this->subtitle,
            'controller_name' => $this->controller_name,
            "icon"=>PATHS["exchangeRates"]["icon"],
            "page_name" => $this->page_name,
            "description" => $this->description,
            "content" => $this->content,
            'routes' => PATHS
        ]);
    }

    private function getSelectedData($selectedData=array('kod'=>'code','nazwa'=>'currency','kurs'=>'/mid','data'=>'/effectiveDate')){
        if($this->content["rates"]!=null){
            $tmp_item = null;
            foreach ($this->content["rates"] as &$item) {
                $tmp_item = array();
                foreach ($selectedData as $key => $select) {
                    if(strpos($select,'/')===false){
                        $tmp_item[$key] = $item->$select;
                    }else{
                        $select=substr($select,1);
                        $tmp_item[$key] = $item->rates[0]->$select;
                    }
                }
                $item = $tmp_item;
            }
        }
    }

    private function getAllRatesOfCurrency(&$data_tmp){
        $tmp=array();
        $data_tmp=array();
        foreach ($this->currency as &$item) {
            if($this->getDataFromAPI($tmp,$item)){
                $data_tmp[]=$tmp;
            }
        }
        if(count($data_tmp)!=0){
            return true;
        }
        return false;
    }

    private function getDataFromAPI(&$data,$addUrl="rates/A/EUR"){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->base_url.''.$addUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);        
        $data = json_decode($response);// If using JSON...
        if($data!=null){
            return true;
        }
        return false;
    }
}
