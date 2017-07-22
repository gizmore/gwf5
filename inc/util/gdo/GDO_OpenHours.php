<?php
class GDO_OpenHours extends GDO_Serialize
{
    public function render()
    {
        return GWF_Template::mainPHP('form/open_hours.php', ['field' => $this]);
    }
    
    public function isOpen(string $date=null)
    {
        $date = $date === null ? GWF_Time::getDate() : $date;
        
    }
    
    public function initJSON()
    {
        return $this->getGDOValue();
    }
    
}
