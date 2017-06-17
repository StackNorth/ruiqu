<?php
class DataviewModule extends CWebModule
{
    public function init()
    {
        $this->setImport(array(
            'dataview.components.*',
            'dataview.controllers.*',
        ));
    }
}
