<?php
/**
 * Basic JS loading and configuration
 * 
 * @author gizmore
 *
 */
class Module_GWF extends GWF_Module
{
	public $module_version = "5.01";
	public $module_priority = 5; # Load early
	
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
// 		# jQuery Datatables
// 		GWF_Javascript::addBowerJavascript("datatables.net/js/jquery.dataTables$min.js");
// 		GWF_Javascript::addBowerJavascript("datatables.net-bs/js/dataTables.bootstrap$min.js");
// 		GWF_Website::addBowerCSS("datatables.net-bs/css/dataTables.bootstrap$min.css");
		# Angular
		GWF_Javascript::addBowerJavascript("angular/angular$min.js");
		# Flow
		GWF_Javascript::addBowerJavascript("flow.js/dist/flow$min.js");
		GWF_Javascript::addBowerJavascript("ng-flow/dist/ng-flow$min.js");
		# Material
		GWF_Javascript::addBowerJavascript("angular-animate/angular-animate$min.js");
		GWF_Javascript::addBowerJavascript("angular-aria/angular-aria$min.js");
		GWF_Javascript::addBowerJavascript("angular-messages/angular-messages$min.js");
		GWF_Javascript::addBowerJavascript("angular-material/angular-material$min.js");
		GWF_Javascript::addBowerJavascript("angular-sanitize/angular-sanitize$min.js");
		GWF_Javascript::addBowerJavascript("angular-ui-router/release/angular-ui-router$min.js");
		GWF_Website::addBowerCSS("angular-material/angular-material$min.css");
		GWF_Website::addCSS("https://fonts.googleapis.com/icon?family=Material+Icons");
// 		# MD Color
// 		GWF_Javascript::addBowerJavascript("tinycolor/dist/tinycolor-min.js");
// 		GWF_Javascript::addBowerJavascript("md-color-picker/dist/mdColorPicker$min.js");
// 		GWF_Website::addBowerCSS("md-color-picker/dist/mdColorPicker$min.css");
		# MD File
		GWF_Javascript::addBowerJavascript("lf-ng-md-file-input/dist/lf-ng-md-file-input$min.js");
		GWF_Website::addBowerCSS("lf-ng-md-file-input/dist/lf-ng-md-file-input$min.css");
		# TinyMCE
// 		GWF_Javascript::addBowerJavascript("tinymce/tinymce$min.js");
// 		GWF_Javascript::addBowerJavascript("angular-ui-tinymce/dist/tinymce.min.js");
		# CKEditor
// 		GWF_Javascript::addBowerJavascript("ckeditor/ckeditor.js");
// 		GWF_Javascript::addBowerJavascript("angular-ckeditor/angular-ckeditor$min.js");
		
		# GWF
		$this->onIncludeGWFScripts();
	}
	
	private function onIncludeGWFScripts()
	{
		$this->addJavascript('js/gwf-module.js');
		
		$this->addJavascript('js/gwf-app-ctrl.js');
		$this->addJavascript('js/gwf-error-srvc.js');
		$this->addJavascript('js/gwf-exception-srvc.js');
		$this->addJavascript('js/gwf-form-ctrl.js');
		$this->addJavascript('js/gwf-loading-srvc.js');
		$this->addJavascript('js/gwf-request-interceptor.js');
		$this->addJavascript('js/gwf-request-srvc.js');
		$this->addJavascript('js/gwf-string-util.js');
		$this->addJavascript('js/gwf-upload-ctrl.js');
		$this->addJavascript('js/gwf-user.js');
		$this->addJavascript('js/ng-crsrup.js');
		$this->addJavascript('js/ng-enter.js');
		$this->addJavascript('js/ng-html.js');
		
		GWF_Javascript::addJavascriptInline($this->gwfConfigJS());
		GWF_Javascript::addJavascriptInline($this->gwfUserJS());
	}

	private function gwfConfigJS()
	{
		return "window.GWF_CONFIG = {};";
	}
	
	public function gwfUserJS()
	{
		$user = GWF_User::current();
// 		$user->loadPermissions();
		$json = json_encode($user->getVars('user_id', 'user_name', 'user_guest_name', 'user_type', 'user_level', 'user_credits'));
		return "window.GWF_USER = new GWF_User($json);";
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
