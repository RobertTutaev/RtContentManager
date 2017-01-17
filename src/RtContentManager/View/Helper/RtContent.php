<?php
namespace RtContentManager\View\Helper;

use Zend\View\Helper\AbstractHelper;

class RtContent extends AbstractHelper
{    
    protected $sm;
    protected $cnfg;
    protected $title;
    protected $user_rule4;
    protected $t_read_more;

    public function __construct($sm) {
        $this->sm   =$sm;
        $this->cnfg =$sm->get('Config');
        
        $t=$sm->get('viewhelpermanager')->get('translate');
        
        $user=$sm->get('zfcuser_auth_service');
        if($user->hasIdentity())
            $this->user_rule4=$user->getIdentity()->GetRule4();
        
        $this->t_read_more  =$t('Read more');
        $this->title        =$t('Edit');
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
        if(!isset($ContentType)) return; 
        
        //4. Подготавливаем параметры для выборки
        
        $d_dt   =$ContentType->GetDisplayDt();
        $d_user =$ContentType->GetDisplayUser();
        $type   =$ContentType->GetDisplayType();
        $type2  =$ContentType->GetLinkType();
        $url    =$this->sm->get('viewhelpermanager')->get('url');
        
        $o_field=$this->cnfg['content_order_field_db'][$ContentType->GetOrderField()];
        $o_desc =$ContentType->GetOrderDesc()?' DESC':'';
        
        //5. Производим выборку
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
        
        //6. Получаем данные
        $data=$query->getResult(\Doctrine\ORM\Query::HYDRATE_OBJECT);        
        
        //7. Подготавливаем результат
        $r='<table class="RTCONTENTTABLE">';
        foreach ($data as $row) {            
            $id         =str_pad($row->GetContentId(),11,'0',STR_PAD_LEFT);
            $link       =$url('contentmanager/content/getb', array('lang'=>$lang, 'id'=>$id, 'layout'=>$layout));            
            $link_edit  =$url('contentmanager/content/edit', array('lang'=>$lang, 'id'=>$id));
            
            $r=$r.
                '<tr>
                    <td>'.
                        //1) Редактирование
                        ($this->user_rule4?'<a href='.$link_edit.' title="'.$this->title.'"><img src="/img/mwp_icons_empty.png" class="mwp-nbtn mwp-nbtn-content"></a>':'').
                        
                        //2) Заголовок
                        ($type!=3&&$type!=5?
                            $row->GetContentType()->GetTagOpen().
                                ($type2==3?
                                    '<a href="'.$link.'" id="cnt'.$id.'">'.$row->GetName().'</a>'
                                    :$row->GetName()
                                ).
                            $row->GetContentType()->GetTagClose()
                            :''
                        ).
                    
                        //3) Частичное отображение 
                        '<div id="vsh'.$id.'"'.
                            ($type==2||$type==3?'>'.$row->GetValueSh():' style="display: none;">').
                        '</div>'.
                    
                        //4) Полное отображение
                        '<div id="vsf'.$id.'"'.
                            ($type==4||$type==5?'>'.$row->GetValueFl():' style="display: none;">').
                        '</div>'.
                            
                        //5) Дата и автор
                        ($d_dt||$d_user?'<font size="1" color="green">'.
                            ($d_dt?$row->GetDt()->format('Y-m-d H:i').' ':'').
                            ($d_user?$row->GetUser()->GetFirstName().' '.$row->GetUser()->GetLastName():'').
                            '</font>'
                            :''
                        ).
                    
                        //6) Кнопка/ссылка
                        ($type==4||$type==5?
                            ''
                            :($type2!=3?
                                '<p><a href="'.$link.'" id="cnt'.$id.'"'.
                                    ($type2==2?' class="btn btn-success btn-lg"'
                                    :'').
                                '>'.$this->t_read_more.'&raquo;</a></p>'
                                :''
                            )
                        ).
                        
                        //7) Передаем тип отображения
                        '<input type="hidden" id="typ'.$id.'" value="'.$type.'">'.
                    
                        //8) Открывать ли новую страницу
                        '<input type="hidden" id="pag'.$id.'" value="'.$row->GetContentType()->GetOpenNewPage().'">'.
                            
                        //9) Передаем тип ссылки
                        '<input type="hidden" id="lnk'.$id.'" value="'.$type2.'">
                    </td>
                </tr>';
        }
        $r=$r.'</table>';
        
        echo $r;
    }
}