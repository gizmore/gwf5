<?php
class GDO_Badge extends GDOType
{
    public $unsigned = true;
    
    public function render() { return GWF_Template::mainPHP('cell/badge.php', ['field' => $this]); }
    public function renderCell() { return $this->render()->getHTML(); }
}
