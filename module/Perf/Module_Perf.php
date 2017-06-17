<?php
/**
 * Performance statistic via bottom panel.
 * @author gizmore
 */
final class Module_Perf extends GWF_Module
{
	public function onRenderFor(GWF_Navbar $navbar)
	{
		if ($navbar->isBottom())
		{
			$navbar->addField(GDO_Template::make()->template('perf-bar.php', [])->module($this));
		}
	}
}
