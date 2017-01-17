<?php
namespace RtContentManager\View\Helper;

use Zend\View\Helper\AbstractHelper;

class RtContentFirstLink extends AbstractHelper
{    
    protected $sm;
    protected $cnfg;
    protected $user_rule4;
    protected $t_read_more;

    public function __construct($sm) {
        $this->sm   =$sm;
        $this->cnfg =$sm->get('Config');
        
        $t=$sm->get('viewhelpermanager')->get('translate');
        
        $user=$sm->get('zfcuser_auth_service');
        if($user->hasIdentity())
            $this->user_rule4=$user->getIdentity()->GetRule4();
        
        $this->t_read_more=$t('Read more');
    }
    
    public function __invoke($ContentTypeId, $lang, $layout='') {
        //1. Получаем objectManager
        $objectManager=$this->sm->get('Doctrine\ORM\EntityManager');
        
        //2. Ищем запись в таблице ContentType
        if(is_int($ContentTypeId))
            $ContentType=$objectManager
                ->getRepository($this->cnfg['content_entity']['contentType'])
                ->find((int)$ContentTypeId);
        else
            $ContentType=$objectManager
                ->getRepository($this->cnfg['content_entity']['contentType'])
                ->findOneBy(array(
                    'name'=>$ContentTypeId));
        
        //3. Если запись не найдена, то выходим
        if(!isset($ContentType)) {
            return;
        }
        
        //4. Производим выборку        
        $o_field=$this->cnfg['content_order_field_db'][$ContentType->GetOrderField()];
        $o_desc =$ContentType->GetOrderDesc()?' DESC':'';
        
        $query=$objectManager->createQuery(
            'SELECT c FROM '.$this->cnfg['content_entity']['content'].' c
            JOIN c.contentType t 
            JOIN c.lang l 
            WHERE t.contentTypeId=:id AND l.name=:lang
            ORDER BY '.$o_field.$o_desc)->
                setParameters(array(
                    'id'    => $ContentType->GetContentTypeId(),
                    'lang'  => $lang,
                ));
        
        //5. Получаем данные
        $data=$query->getResult(\Doctrine\ORM\Query::HYDRATE_OBJECT);        
        
        //6. Подготавливаем результат
        foreach ($data as $row) {
            $id     =str_pad($row->GetContentId(),11,'0',STR_PAD_LEFT);
            $url    =$this->sm->get('viewhelpermanager')->get('url');
            return $url('contentmanager/content/getb', array('lang'=>$lang, 'id'=>$id, 'layout'=>$layout));
        }
        
        return;
    }
}