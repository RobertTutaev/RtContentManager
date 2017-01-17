<?php

namespace RtContentManager\Model;

class ClassLoader {
    
    public function getClass($class) {
        return new $class();
    }
}