<?php
namespace RtContentManager\View\Helper;

use Zend\View\Helper\AbstractHelper;

class RtDisplayType extends AbstractHelper
{    
    private $type;

    public function __construct($sm) {
        $cnfg=$sm->get('Config');
        $t=$sm->get('viewhelpermanager')->get('translate');
        
        foreach($cnfg['content_display_type'] as $i => $value) $this->type[$i]=$t($value);
    }

    public function __invoke($DisplayType) {
        return $this->type[$DisplayType];
    }
}