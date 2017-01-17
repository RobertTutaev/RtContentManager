<?php
namespace RtContentManager\View\Helper;

use Zend\View\Helper\AbstractHelper;

class RtLinkType extends AbstractHelper
{    
    private $type;

    public function __construct($sm) {
        $cnfg=$sm->get('Config');
        $t=$sm->get('viewhelpermanager')->get('translate');
        
        foreach($cnfg['content_link_type'] as $i => $value) {
            $this->type[$i]=$t($value);
        }
    }

    public function __invoke($LinkType) {
        return $this->type[$LinkType];
    }
}