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
	
	/**
	 * Add CORS headers on non cli requests.
	 * {@inheritDoc}
	 * @see GWF_Module::onInit()
	 */
	public function onInit()
	{
		if (!GWF5::instance()->isCLI())
		{
			if ($this->cfgCORS())
			{
				header("Access-Control-Allow-Origin: ".$_SERVER['SERVER_NAME']);
				header("Access-Control-Allow-Credentials: true");
			}
		}
	}

	public function onInstall()
	{
	    if ($this->cfgSiteBirthdate() === null)
	    {
	        $this->saveConfigVar('site_birthdate', GWF_Time::getDate());
	    }
	}
	
	##############
	### Config ###
	##############
	public function getUserSettings()
	{
		return array(
			GDO_DateTime::make('user_birthdate')->label('birthdate'),
			GDO_Checkbox::make('user_hide_online')->initial('0'),
			GDO_Checkbox::make('user_want_adult')->initial('0'),
			GDO_Checkbox::make('user_allow_email')->initial('0'),
			GDO_Checkbox::make('user_show_birthdays')->initial('0'),
		);
	}
	public function getConfig()
	{
		return array(
		    GDO_Date::make('site_birthdate'),
			GDO_Divider::make('div_page')->label('div_pagination'),
			GDO_Int::make('ipp')->initial('20')->max(1000)->unsigned(),
			GDO_Int::make('spr')->initial('10')->max(1000)->unsigned(),
			GDO_Divider::make('div_javascript')->label('div_javascript'),
			GDO_Checkbox::make('cors_header')->initial('1'),
			GDO_Enum::make('minify_js')->enumValues('no', 'yes', 'concat')->initial('no'),
			GDO_Path::make('nodejs_path')->initial('/usr/bin/nodejs')->label('nodejs_path'),
			GDO_Path::make('uglifyjs_path')->initial('uglifyjs')->label('uglifyjs_path'),
			GDO_Path::make('ng_annotate_path')->initial('ng-annotate')->label('ng_annotate_path'),
			GDO_Link::make('link_node_detect')->href(href('GWF', 'DetectNode')),
		);
	}
	public function cfgSiteBirthdate() { return $this->getConfigValue('site_birthdate'); }
	public function cfgItemsPerPage() { return $this->getConfigValue('ipp'); }
	public function cfgMaxSuggestions() { return $this->getConfigValue('spr'); }
	public function cfgCORS() { return $this->getConfigValue('cors_header'); }
	public function cfgMinifyJS() { return $this->getConfigVar('minify_js'); }
	public function cfgNodeJSPath() { return $this->getConfigVar('nodejs_path'); }
	public function cfgUglifyPath() { return $this->getConfigVar('uglifyjs_path'); }
	public function cfgAnnotatePath() { return $this->getConfigVar('ng_annotate_path'); }
	
	public function onIncludeScripts()
	{
		$min = $this->cfgMinifyJS() !== 'no' ? '.min' : '';
		
		# jQuery
		GWF_Javascript::addBowerJavascript("jquery/dist/jquery$min.js");
		GWF_Javascript::addBowerJavascript("bootstrap/dist/js/bootstrap$min.js");
		GWF_Website::addBowerCSS("bootstrap/dist/css/bootstrap$min.css");
		# jQuery UI
		GWF_Javascript::addBowerJavascript("jquery-ui/jquery-ui$min.js");
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
		# Voting
		GWF_Javascript::addBowerJavascript("angular-jk-rating-stars/dist/jk-rating-stars$min.js");
		GWF_Website::addBowerCSS("angular-jk-rating-stars/dist/jk-rating-stars$min.css");
		# Treeview
// 		GWF_Javascript::addBowerJavascript("angular-ivh-treeview/dist/ivh-treeview$min.js");
// 		GWF_Website::addBowerCSS("angular-ivh-treeview/dist/ivh-treeview-theme-basic.css");
// 		GWF_Website::addBowerCSS("angular-ivh-treeview/dist/ivh-treeview.css");
		# DragDrop
		GWF_Javascript::addBowerJavascript("angular-dragdrop/src/angular-dragdrop$min.js");
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
		$this->addJavascript('js/gwf-list-ctrl.js');
		$this->addJavascript('js/gwf-loading-srvc.js');
		$this->addJavascript('js/gwf-request-interceptor.js');
		$this->addJavascript('js/gwf-request-srvc.js');
		$this->addJavascript('js/gwf-sort-ctrl.js');
		$this->addJavascript('js/gwf-string-util.js');
		$this->addJavascript('js/gwf-table-ctrl.js');
		$this->addJavascript('js/gwf-tree.js');
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
	
	public function gwfUserJSON()
	{
		$user = GWF_User::current();
		$json = array(
			'user_id' => (int)$user->getID(),
			'user_name' => $user->getName(),
			'user_guest_name' => $user->getGuestName(),
			'user_type' => $user->getType(),
			'user_level' => (int)$user->getLevel(),
			'user_credits' => (int)$user->getCredits(),
			'permissions' => $user->loadPermissions(),
		);
		return $json;
	}
	
	public function gwfUserJS()
	{
		$json = json_encode($this->gwfUserJSON());
		return "window.GWF_USER = new GWF_User($json);";
	}
	
	###############
	### Navbars ###
	###############
	public function onRenderFor(GWF_Navbar $navbar)
	{
		if ($navbar->isRight())
		{
// 			$navbar->addField(GDO_Label::make()->label('gwf_sidebar_version', [GWF_CORE_VERSION]));
		}
		
// 		if ($navbar->isBottom() && $this->cfgCopyright())
		{
			
		}

	}
}
