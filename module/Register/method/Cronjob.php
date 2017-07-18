<?php
final class Register_Cronjob extends GWF_MethodCronjob
{
    public function run()
    {
        $module = Module_Register::instance();
        if (0 != ($timeout = $module->cfgEmailActivationTimeout()))
        {
            $cut = GWF_Time::getDate(time() - $timeout);
            GWF_UserActivation::table()->deleteWhere("ua_time < '$cut'")->exec();
            if ($affected = GDODB::instance()->affectedRows())
            {
                $this->logNotice("Deleted $affected old user activations.");
            }
        }
    }
}
