<?php
class GDO_Toolbar extends GDOType
{
    use GWF_Fields;
    
    public function render()
    {
        return GWF_Template::mainPHP('cell/toolbar.php', ['field' => $this]);
    }
    
    public function renderCell()
    {
        return $this->render()->getHTML();
        
    }
    
}
