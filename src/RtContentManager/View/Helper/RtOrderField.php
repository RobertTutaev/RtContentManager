<?php
namespace RtContentManager\View\Helper;

use Zend\View\Helper\AbstractHelper;

class RtOrderField extends AbstractHelper
{    
    private $type;

    public function __construct($sm) {
        $cnfg=$sm->get('Config');
        $t=$sm->get('viewhelpermanager')->get('translate');
        
        foreach($cnfg['content_order_field'] as $i => $value) $this->type[$i]=$t($value);
    }

    public function __invoke($OrderField) {
        return $this->type[$OrderField];
    }
}