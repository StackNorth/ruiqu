<?php
class ApiModule extends CWebModule
{
    public function init()
    {
        $this->setImport(array(
            'api.controllers.*',
        ));
    }
}