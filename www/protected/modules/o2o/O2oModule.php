<?php
class O2oModule extends CWebModule
{
    public function init()
    {
        $this->setImport(array(
            'o2o.controllers.*',
            'o2o.components.*',

        ));
    }
}
