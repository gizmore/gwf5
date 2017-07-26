<?php
final class GWF_Config extends GWF_Method
{
    public function execute()
    {
        $json = [];
        $modules = GWF_ModuleLoader::instance()->getActiveModules();
        foreach ($modules as $module)
        {
            $json[$module->getName()] = $this->getModuleConfig($module);
        }
        return new GWF_Response($json);
    }
    
    public function getModuleConfig(GWF_Module $module)
    {
        $json = [];
        foreach ($module->getConfigCache() as $type)
        {
            if (!$type instanceof GDO_Blank)
            {
                $json[$type->name] = $module->getConfigVar($type->name);
            }
        }
        return $json;
    }
    
}
