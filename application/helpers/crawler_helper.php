<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Jaybizzle\CrawlerDetect\CrawlerDetect;

class Crawler_helper {

    function __construct(){ }
    
    static function detectar_bot(){
        $ci =& get_instance();
        $bot = $ci->input->user_agent();
        $detect = new CrawlerDetect;
        if( $detect->isCrawler($bot)) {
            return show_404();  
        }else{
            return FALSE; 
        }
    }

    static function verificar_bot(){
        $ci =& get_instance();
        $bot = $ci->input->user_agent();
        $detect = new CrawlerDetect;
        if( $detect->isCrawler($bot)) {
            return TRUE;  
        }else{
            return FALSE; 
        }
    }
}
