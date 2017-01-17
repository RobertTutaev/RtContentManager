<?php

namespace RtContentManager\Model;

class LinkPreviousPage {
    
    private $sm;
    
    public function __construct($sm){
        $this->sm=$sm;
    }
    
    public function getLink() {
        //1. Определяем URL для возврата назад
        $referer=$this->sm->get('Request')->getHeader('Referer');
        if($referer){
            $refererUrl =$referer->uri()->getPath();                    //Адрес
            $refererHost=$referer->uri()->getHost();                    //Хост
            $currentHost=$this->sm->get('Request')->getUri()->getHost();//Текущий хост

            //Если запрос от текщего хоста, то перенаправление на предыдущую страницу
            if($refererUrl && $refererHost == $currentHost){
                //$link=$this->redirect()->toUrl($refererUrl);
                $link=$refererUrl;
            }
        }
        
        //2. Если пользователь зашел с другого сайта
        if(!isset($link)){
            //то определяем маршрут по умолчанию
            $config=$this->sm->get('Config');
            $url=$this->sm->get('viewhelpermanager')->get('url');
            
            $link=$url($config['content_default_url']);
        }
        return $link;
    }
}