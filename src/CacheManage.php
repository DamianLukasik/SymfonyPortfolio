<?php 
namespace App;

class CacheManage
{
    private static $instance;
    private function __construct() {
        if(!isset($_SESSION)){
            session_start();
        }
    }
    private function __clone() {}
    public static function getInstance() {
        if(self::$instance === null) {
            self::$instance = new CacheManage();
        }
        return self::$instance;
    }

    public function isExistInCache($what){
        if(isset($_SESSION[$what])){
            return true;
        }
        return false;
    }

    public function LoadFromCache(&$data,$what){
        if(isset($_SESSION[$what])){
            $data=($_SESSION[$what]);
            $_SESSION['communicate_cache']='Dane odczytane z cache';
        }
    }

    public function SaveToCache(&$data,$in){
        if(isset($_SESSION)){
            $_SESSION[$in] = $data;
        }
    }

    public function getCommunicate(){
        $cache_communicate=null;
        if(isset($_SESSION['communicate_cache'])){
            $cache_communicate=$_SESSION['communicate_cache'];
            unset($_SESSION['communicate_cache']);
        }
        return $cache_communicate;
    }
}
?>