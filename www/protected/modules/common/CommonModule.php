<?php

class CommonModule extends CWebModule
{
    public function init()
    {

        $this->setImport(array(
            'common.controllers.*',
            'common.components.*',
            'o2o.components.*',
        ));
    }
}
