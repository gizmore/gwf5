<?php
/**
 * GDOType is the base class for all datatypes used in GWFv5.
 * Imagine it like a column descriptor with conversions and stuff.
 * 
 * You can add them to GWF_Form::addField()
 * You can add them to GDO::gdoColumns()
 * You can add them to GWF_Module::getConfig()
 * 
 * @author gizmore
 * @since 5.0
 * 
 * @see GDO
 * @see GWF_Form
 */
abstract class GDOType
{
	public $name;
	public $label;
	public $tooltip;
	public $placeholder;
	
	public $value = null;
	public $oldValue = null;
	public $initial = null;
	public $readable = true;
	public $writable = true;
	
	public $error;
	public $validators;

	public $index = false;
	public $primary = false;
	public $null = true;
	
	################
	### Abstract ###
	################
	/**
	 * Return table creation structure here.
	 * @see GDO_Int
	 * @see GDO_String
	 * @see GDO_Enum
	 */
	public function gdoColumnDefine() {}
	
	###############
	### Factory ###
	###############
	/**
	 * Create a @{GDOType} instance.
	 * @param string $name
	 * @return self
	 */
	public static function make(string $name=null)
	{
		$type = get_called_class();
		$obj = new $type();
		return $name ? $obj->name($name) : $obj;
	}
	
	#############
	### Error ###
	#############
	public function error(string $key, array $args = null)
	{
		$this->error = t($key, $args);
		return false;
	}
	
	public function classError()
	{
		return $this->error ? ' has-error' : '';
	}
	
	public function displayError()
	{
		return $this->error ? GWF_HTML::escape($this->error) : '';
	}
	
	############
	### Name ###
	############
	public function name(string $name)
	{
		$this->name = $name;
		return $this->defaultLabel();
	}
	
	public function defaultLabel()
	{
		return $this->label($this->name);
	}
	
	public function identifier()
	{
		return GDO::quoteIdentifierS($this->name);
	}

	#################
	### Validator ###
	#################
	public function validator($callback)
	{
		$this->validators = $this->validators ? $this->validators : [];
		$this->validators[] = $callback;
		return $this;
	}
	
	#############
	### Label ###
	#############
	public function noLabel()
	{
		$this->label = null;
		return $this;
	}
	
	public function label(string $key, array $args=null)
	{
		$this->label = t($key, $args);
		return $this;
	}
	
	public function displayLabel()
	{
		return $this->label ? $this->label : '';
	}
	
	public function displayHeaderLabel()
	{
		return $this->displayLabel();
	}
	####################
	### Table Filter ###
	####################
	public function renderFilter()
	{
		return '';
	}
	
	public function filterQuery(GDOQuery $query)
	{
		return $query;
	}
	
	###################
	### Placeholder ###
	###################
	public function placeholder(string $placeholder, array $args=null)
	{
		$this->placeholder = t($placeholder, $args);
		return $this;
	}
	
	public function displayPlaceholder()
	{
		return $this->placeholder ? GWF_HTML::escape($this->placeholder) : '';
	}
	
	###############
	### Tooltip ###
	###############
	public function tooltip(string $key)
	{
		$this->tooltip = $key;
		return $this;
	}
	
	public function displayTooltip(array $args=null)
	{
		return $this->tooltip ? t($this->tooltip, $args) : '';
	}
	
	############
	### Icon ###
	############
	public static function matIconS(string $icon) { return "<md-icon class=\"material-icons\">$icon</md-icon>"; }
// 	public static function aweIconS(string $icon) { return "<md-icon class=\"material-icons\">$icon</md-icon>"; }
	
	private $icon;
	public function htmlIcon() { return $this->icon ? $this->icon : ''; }
	public function rawIcon(string $icon) { $this->icon = $icon; return $this; }
	public function matIcon(string $icon) { return $this->rawIcon(self::matIconS($icon)); }
	
	###############
	### Default ###
	###############
	public function initial($initial)
	{
		$this->initial = (string)$initial;
		return $this->notNull();
	}
	
	public function gdoInitialDefine()
	{
		return $this->initial ? (" DEFAULT ".GDO::quoteS($this->initial)) : '';
	}
	
	public function blankData()
	{
		return array($this->name => $this->initial);
	}
	
	#############
	### Value ###
	#############
	public $gdo;
	public function gdo(GDO $gdo)
	{
		$this->gdo = $gdo;
		return $this;
	}
	
	public function getGDOVar()
	{
		return $this->gdo->getVar($this->name);
	}
	
	public function value($value)
	{
		$this->value = $value;
		return $this;
	}
	
	public function getValue()
	{
		return $this->value === null ? $this->initial : $this->value;
	}
	
	/**
	 * Get the value converted to specialized entity.
	 * @return mixed
	 */
	public function getGDOValue()
	{
		return $this->getValue();
	}
	
	public function setGDOValue($value)
	{
		return $this;
	}
	
	/**
	 * Get value as sent by the user.
	 * @return string
	 */
	public function formValue()
	{
		$vars = Common::getRequestArray('form', []);
		return isset($vars[$this->name]) ? $vars[$this->name] : $this->getValue();
	}
	
	public function filterValue()
	{
		$vars = Common::getRequestArray('f', []);
		return @$vars[$this->name];
	}
	
	public function displayFilterValue()
	{
		return GWF_HTML::escape($this->filterValue());
	}
	
	public function displayFormValue()
	{
		return GWF_HTML::escape($this->formValue());
	}
	
	public function quotedValue()
	{
		return GDO::quoteS($this->formValue());
	}
	
	##############
	### Events ###
	##############
	public function gdoBeforeCreate() {}
	public function gdoBeforeUpdate(GDOQuery $query) {}
	public function gdoBeforeDelete() {}
	public function gdoAfterCreate() {}
	public function gdoAfterUpdate() {}
	public function gdoAfterDelete() {}

	##################
	### Read/Write ###
	##################
	public function readable(bool $readable)
	{
		$this->readable = $readable;
		return $this;
	}
	
	public function writable(bool $writable)
	{
		$this->writable = $writable;
		return $this;
	}
	
	public function htmlDisabled()
	{
		return $this->writable ? '' : ' disabled="disabled"';
	}
	
	############
	### NULL ###
	############
	public function notNull()
	{
		$this->null = false;
		return $this;
	}
	
	public function gdoNullDefine()
	{
		return $this->null ? "" : " NOT NULL";
	}
	
	
	public function required()
	{
		return $this->notNull();
	}
	
	public function htmlRequired()
	{
		return $this->null ? "" : ' required="required"';
	}
	
	############
	### Keys ###
	############
	public function index()
	{
		$this->index = true;
		return $this;
	}
	
	public function unique()
	{
		$this->unique = true;
		return $this;
	}
	
	public function primary()
	{
		$this->primary = true;
		return $this->notNull();
	}
	
	public function isPrimary()
	{
		return $this->primary;
	}
	
	#############
	### Class ###
	#############
	public function gdoClassName()
	{
		return get_called_class();
	}
	
	public function htmlClass()
	{
		return sprintf(' class="%s"', str_replace('_', '-', strtolower($this->gdoClassName())));
	}
	
	
	##############
	### Render ###
	##############
	public function __toString() { return $this->render()->__toString(); }
	public function toJSON() { return array($this->name => $this->getGDOValue()); }
	
	public function render()
	{
		return '';
	}

	public function renderJSON()
	{
		return array(
			'name' => $this->name,
			'error' => $this->error,
			'type' => get_called_class(),
			'value' => $this->jsonFormValue(),
		);
	}
	
	public function jsonFormValue()
	{
		return $this->formValue();
	}
	
	public function gdoRenderCell()
	{
		$method = "render_{$this->name}";
		if (method_exists($this->gdo, $method))
		{
			return call_user_func([$this->gdo,$method]);
		}
		return $this->renderCell();
	}
	
	public function renderCell()
	{
		$value = $this->gdo ? $this->gdo->getVar($this->name) : $this->getValue();
		return GWF_HTML::escape($value);
	}
	
	##################
	### Validation ###
	##################
	public function formValidate(GWF_Form $form)
	{
		$value = $this->filteredFormValue();
		if (($this->validate($value)) && ($this->validatorsValidate($form)))
		{
			# Add form value if validated.
			$this->addFormValue($form, $value);
			return true;
		}
	}
	
	protected function filteredFormValue()
	{
		if ($value = $this->formValue())
		{
			$value = trim($value);
		}
		return $value === '' ? null : $value;
	}
	
	public function addFormValue(GWF_Form $form, $value)
	{
		$this->oldValue = $this->getValue();
		$this->value = $value;
		$form->addValue($this->name, $this->value);
	}
	
	public function hasChanged()
	{
		return $this->oldValue != $this->value;
	}
	
	public function validatorsValidate(GWF_Form $form)
	{
		if ($this->validators)
		{
			foreach ($this->validators as $validator)
			{
				if (!call_user_func($validator, $form, $this))
				{
					return false;
				}
			}
		}
		return true;
	}
	
	public function validate($value)
	{
		return $value === null ? ($this->null ? true : $this->error('err_is_null', [$this->name])) : true;
	}
	
	
	public function onValidated() { unset($_POST[$this->name]); }

	###################
	### Flow upload ###
	###################
	public function flowUpload() {}
	public function cleanup() {}
}
