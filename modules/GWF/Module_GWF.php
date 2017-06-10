<?php
class Module_GWF extends GWF_Module
{
	public $module_version= "5.01";
	
	public function isCoreModule() { return true; }
	public function onLoadLanguage() { return $this->loadLanguage('lang/gwf'); }

	##############
	### Config ###
	##############
	public function getConfig()
	{
		return array(
			GDO_Int::make('ipp')->initial('20')->max(1000)->unsigned(),
			GDO_Enum::make('minify_js')->enumValues('no', 'yes', 'concat')->initial('no'),
		);
	}
	public function cfgItemsPerPage() { return $this->getConfigValue('ipp'); }
	public function cfgMinifyJS() { return $this->getConfigValue('minify_js'); }

	public function onIncludeScripts()
	{
		$min = $this->cfgMinifyJS() !== 'no' ? '.min' : '';
		# jQuery
		GWF_Javascript::addBowerJavascript("jquery/dist/jquery$min.js");
		GWF_Javascript::addBowerJavascript("bootstrap/dist/js/bootstrap$min.js");
		GWF_Website::addBowerCSS("bootstrap/dist/css/bootstrap$min.css");
// 		GWF_Javascript::addBowerJavascript("datatables.net/js/jquery.dataTables$min.js");
// 		GWF_Javascript::addBowerJavascript("datatables.net-bs/js/dataTables.bootstrap$min.js");
// 		GWF_Website::addBowerCSS("datatables.net-bs/css/dataTables.bootstrap$min.css");
		# Angular
		GWF_Javascript::addBowerJavascript("angular/angular$min.js");
		# Flow
		GWF_Javascript::addBowerJavascript("flow.js/dist/flow$min.js");
		GWF_Javascript::addBowerJavascript("ng-flow/dist/ng-flow$min.js");
		GWF_Javascript::addBowerJavascript("lf-ng-md-file-input/dist/lf-ng-md-file-input$min.js");
		GWF_Website::addBowerCSS("lf-ng-md-file-input/dist/lf-ng-md-file-input$min.css");
		# Material
		GWF_Javascript::addBowerJavascript("angular-animate/angular-animate$min.js");
		GWF_Javascript::addBowerJavascript("angular-aria/angular-aria$min.js");
		GWF_Javascript::addBowerJavascript("angular-messages/angular-messages$min.js");
		GWF_Javascript::addBowerJavascript("angular-material/angular-material$min.js");
		GWF_Javascript::addBowerJavascript("angular-sanitize/angular-sanitize$min.js");
		GWF_Javascript::addBowerJavascript("angular-ui-router/release/angular-ui-router$min.js");
		GWF_Website::addBowerCSS("angular-material/angular-material$min.css");
		GWF_Website::addCSS("https://fonts.googleapis.com/icon?family=Material+Icons");
		# GWF
		$this->onIncludeGWFScripts();
	}
	
	private function onIncludeGWFScripts()
	{
		$this->addJavascript('js/gwf-module.js');
		$this->addJavascript('js/gwf-app-ctrl.js');
		$this->addJavascript('js/gwf-form-ctrl.js');
		$this->addJavascript('js/gwf-upload-ctrl.js');
	}

	###############
	### Navbars ###
	###############
	public function onRenderFor(GWF_Navbar $navbar)
	{
		if ($navbar->isRight())
		{
			$navbar->addField(GDO_Label::make()->label('gwf_sidebar_version', [GWF_CORE_VERSION]));
		}

	}
}
