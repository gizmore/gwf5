<?php
class GDO_Secret extends GDO_String
{
    public function __construct()
    {
        $this->ascii();
        $this->caseS();
    }
}
