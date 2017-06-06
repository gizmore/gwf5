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
	public static function make($name=null)
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
		$this->error = GWF_Trans::t($key, $args);
		return false;
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
		return $this->label($name);
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
		$this->validators = $this->validators ? $this->validators : array();
		$this->validators[] = $callback;
		return $this;
	}
	
	#############
	### Label ###
	#############
	public function label(string $label)
	{
		$this->label = $label;
		return $this;
	}
	
	public function noLabel()
	{
		$this->label = null;
		return $this;
	}
	
	public function displayLabel(array $args=null)
	{
		return $this->label ? GWF_Trans::t($this->label, $args): '';
	}
	
	###################
	### Placeholder ###
	###################
	public function placeholder(string $placeholder, array $args=null)
	{
		$this->placeholder = GWF_Trans::t($placeholder, $args);
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
		return $this->tooltip ? GWF_Trans::t($this->tooltip, $args) : '';
	}
	
	###############
	### Default ###
	###############
	public function initial($initial)
	{
		$this->initial= $initial;
		return $this;
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
		return isset($vars[$this->name]) ? (string)$vars[$this->name] : $this->value;
	}
	
	public function displayFormValue()
	{
		$value = $this->formValue();
		return $value ? GWF_HTML::escape($value) : '';
	}
	
// 	/**
// 	 * Get the GDO/GWF Value for this column.
// 	 * Used in references etc?
// 	 * @return string
// 	 */
// 	public function gdoValue(GDO $gdo, $value)
// 	{
// 		return $value;
// 	}
	
	public function gdoDisplay(GDO $gdo, $value)
	{
		if ($value === null)
		{
			return "<i>null</i>";
		}
		if ($value === false)
		{
			return "<i>false</i>";
		}
		if ($value === true)
		{
			return "<i>true</i>";
		}
		return GWF_HTML::escape($value);
	}
	
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
	public function render()
	{
		return '';
	}

	public function renderJSON()
	{
		return array(
			'name' => $this->name,
			'error' => $this->error,
			'type' => get_class($this),
			'value' => $this->formValue(),
		);
	}
	
	##################
	### Validation ###
	##################
	public function formValidate(GWF_Form $form)
	{
		$value = $this->formValue();
		if (($this->validate($value)) && ($this->validatorsValidate()))
		{
			# Add form value if validated.
			$this->addFormValue($form, $value);
			return true;
		}
	}
	
	public function addFormValue(GWF_Form $form, $value)
	{
		$this->oldValue = $this->getValue();
		$this->value = $value;
		$form->addValue($this->name, $value);
	}
	
	public function hasChanged()
	{
		return $this->oldValue != $this->value;
	}
	
	public function validatorsValidate()
	{
		if ($this->validators)
		{
			foreach ($this->validators as $validator)
			{
				if (!call_user_func($validator, $this))
				{
					return false;
				}
			}
		}
		return true;
	}
	
	public function validate($value)
	{
		return true;
	}
}
