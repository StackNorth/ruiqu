<?php
class TechModule extends CWebModule
{
    public function init()
    {
        $this->setImport(array(
            'tech.controllers.*',
            'tech.components.*',

        ));
    }
}
