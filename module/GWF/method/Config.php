<?php
/**
 * API Request to get all module configs.
 * Useful for JS Apps.
 * @author gizmore
 */
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
    
    private function getModuleConfig(GWF_Module $module)
    {
        $json = [];
        foreach ($module->getConfigCache() as $type)
        {
            if ( (!$type instanceof GDO_Blank) && 
                 (!$type instanceof GDO_Secret) )
            {
                $json[$type->name] = $module->getConfigValue($type->name);
            }
        }
        return $json;
    }
}
