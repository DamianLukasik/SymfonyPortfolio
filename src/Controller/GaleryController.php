<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Crew\Unsplash;
use App\CacheManage;
require_once __DIR__.'/../OnlyReadVariables.php';

class GaleryController extends AbstractController
{
    private $search;
    private $orientation;
    private $page;
    private $galery_name = "Galeria";
    private $description;
    private $paginator = array();
    private $per_page = 20;
    private $title = "Provider";
    private $subtitle = "Galeria";
    private $page_name = "";
    private $controller_name = 'GaleryController';
    private $cache_manage = null;

    public function __construct()
    {
        if(!isset($_SESSION)){
            session_start();
        }
        $this->cache_manage=CacheManage::getInstance();
        $this->InitUnsplash();
    }

    private function generateContent(Request &$request){
        $this->LoadParams($request);
        $this->description = "Wszystkie wyniki wyszukiwania dla słowa \"".$this->search."\":";
        $this->LoadPaginator();   
        $content = array();     
        $this->LoadImages($content);
        if(count($content)==0){
            $this->description = "Brak wyników wyszukiwania dla słowa \"".$this->search."\":";
        }
        $galery = [ "name" => $this->galery_name, "content" => $content];

        return [
            "title" => $this->title,
            "subtitle" => $this->subtitle,
            "controller_name" => $this->controller_name,
            "page_name" => $galery['name'],
            "content" => $galery['content'],
            "paginator" => $this->paginator,
            "description" => $this->description,
            "icon"=>PATHS["galery"]["icon"],
            "routes" => PATHS,
            "cache_communicate" => $this->cache_manage->getCommunicate()
        ];
    }

    /**
     * @Route("/galery", name="galery")
     */
    public function index(Request $request)
    {     
        $this->cache_manage->LoadFromCache($this->page,'currentPage');
        return $this->render('galery/index.html.twig', $this->generateContent($request));
    }

    /**
     * @Route("/galery/next", name="galery/pageNext")
     */
    public function pageNext(Request $request)
    {        
        $this->cache_manage->LoadFromCache($this->page,'currentPage');
        if($this->page==null){
            $this->page=2;
        }else{
            $this->page=$this->page+1;
        }
        return $this->render('galery/index.html.twig', $this->generateContent($request));
    }

    /**
     * @Route("/galery/previous", name="galery/pagePrevious")
     */
    public function pagePrevious(Request $request)
    {
        $this->cache_manage->LoadFromCache($this->page,'currentPage');
        if($this->page!=null){
            $this->page=$this->page-1;
        }
        return $this->render('galery/index.html.twig', $this->generateContent($request));
    }

    /**
     * @Route("/galery/page/{id}", name="galery/page")
     */
    public function page(Request $request,$id)
    {
        if($id!=null){
            $this->page=(int)$id;
        }
        return $this->render('galery/index.html.twig', $this->generateContent($request));
    }

    private function LoadParams(Request $request){
        $this->search = $request->query->get('search');
        $this->orientation = $request->query->get('orientation');
        if($this->page==null){
            $this->page = $request->query->get('page');
        }        
    }

    private function LoadPaginator(){
        $url_=array();
        $urlPrevious=array();
        $urlPage=array();
        $urlNext=array();
        if($this->search==null){
            $this->search="cat";
            $this->galery_name = "Galeria kotów";
            $this->description = "Najsłodsze koty w Internecie:";
        }else{
            $url_[]="search=".$this->search;
        }    
        if($this->page==null){
            $this->page=1; 
        }      
        if($this->orientation==null){
            $this->orientation="landscape";
        }else{
            $url_[]="orientation=".$this->orientation;
        }   
        if(count($url_)!=0){
            $urlNext="/galery/next?".implode('&',$url_);
        }else{
            $urlNext="/galery/next";
        }   
        if(count($url_)!=0){
            $urlPrevious="/galery/previous?".implode('&',$url_);
        }else{
            $urlPrevious="/galery/previous";
        }   
        if(count($url_)!=0){
            $urlPage="/galery/page/%page%?".implode('&',$url_);
        }else{
            $urlPage="/galery/page/%page%";
        }      
        $this->paginator = array(
            "totalPages"=>0,
            "currentPage"=>$this->page,
            "urlPrevious"=>$urlPrevious,
            "urlNext"=>$urlNext,
            "urlPage"=>$urlPage
        );
        $this->cache_manage->SaveToCache($this->page,'currentPage');
    }

    private function InitUnsplash(){
        \Crew\Unsplash\HttpClient::init([
            'applicationId'	=> '01e4e014015fb596092de4ddebf747bbf10abc47633626cb3230085e16c73c31',
            'secret'		=> '52eb0f4da1dd40007609180874de7b97e8abfc862c309f69f3f8bd66f3cf5656',
            'callbackUrl'	=> 'https://your-application.com/oauth/callback',
            'utmSource' => 'Test'
        ]);
        $scopes = ['public', 'write_user']; 
        \Crew\Unsplash\HttpClient::$connection->getConnectionUrl($scopes);
    }

    private function LoadImages(&$content){
        $photos = null;
        $marker="galery?".implode('&',['search='.$this->search, 'page='.$this->page, 'per_page='.$this->per_page, 'orientation='.$this->orientation]);
        //sprawdź czy jest w cache
        if($this->cache_manage->isExistInCache($marker)){
            //jeśli tak odczytak,
            $this->cache_manage->LoadFromCache($photos,$marker);//? odczyt obiektu?
        }else{
            //jeśli nie zadaj nowe zapytanie
            $photos = \Crew\Unsplash\Search::photos($this->search, $this->page, $this->per_page, $this->orientation);
            //zapisz w cache
            $this->cache_manage->SaveToCache($photos,$marker);//zapis obiektu do cache?
        }
        foreach ($photos->getResults() as &$photo) {
            array_push($content,["urls"=>$photo['urls'],"name"=>$photo['alt_description']]);            
        }
        $this->paginator["totalPages"]=$photos->getTotalPages();
    }
}
