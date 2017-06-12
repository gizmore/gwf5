<?php
/**
 * New implementation of GWF_Form based on GDOType5
 * 
 * @author gizmore
 * @version 5.0
 */
class GWF_Form
{
	use GWF_Fields;
	
	/**
	 * @var string[]
	 */
	private $values;
	
	/**
	 * @var string[]
	 */
	private $initialValues;
	
	#
	private $title;
	private $info;
	private $action;
	private $method;
	private $enctype;

	################
	### Creation ###
	################
	public function __construct()
	{
		$this->title = null;
		$this->action = $_SERVER['REQUEST_URI'];
		$this->method = 'post';
		$this->enctype = 'application/x-www-form-urlencoded';
	}
	
	public function title(string $key, array $args=null)
	{
		$this->title = t($key, $args);
		return $this;
	}
	
	public function info(string $key, array $args=null)
	{
		$this->info = t($key, $args);
		return $this;
	}
	
	##################
	### Validation ###
	##################
	public function validate()
	{
		$valid = true;
		foreach ($this->fields as $field)
		{
			if ($field->writable)
			{
				if (!$field->formValidate($this))
				{
					if (!$field->error)
					{
						$field->error('err_field_invalid', [$field->displayLabel()]);
					}
					$valid = false;
				}
			}
		}
		
		if ($valid)
		{
			$this->onValidated();
		}
		
		return $valid;
	}
	
	public function onValidated()
	{
		foreach ($this->fields as $gdoType)
		{
			$gdoType->onValidated();
		}
	}
	
	##############
	### Values ###
	##############
	public function withGDOValuesFrom(GDO $gdo)
	{
		$vars = $gdo->getGDOVars();
		foreach ($this->fields as $field)
		{
			if (isset($vars[$field->name]))
			{
				$field->value($vars[$field->name]);
			}
		}
		return $this;
	}
	
	public function values()
	{
		return $this->values;
	}
	
	public function getVar(string $key)
	{
		return $this->values[$key];
	}
	
	public function addValue(string $key=null, $value)
	{
		if (!$this->values)
		{
			$this->values = [];
		}
		$this->values[$key] = $value;
		return $this;
	}
	
	##############
	### Render ###
	##############
	public function render()
	{
		switch (GWF5::instance()->getFormat())
		{
			case 'html': default: return $this->renderHTML();
			case 'json': return $this->renderJSON();
		}
	}

	private function renderHTML()
	{
		$tVars = array(
			'fields' => $this->fields,
			'title' => GWF_HTML::escape($this->title),
			'info' => $this->info,
			'action' => GWF_HTML::escape($this->action),
			'method' => $this->method,
			'enctype' => $this->enctype,
		);
		return GWF_Template::mainPHP('form.php', $tVars);
	}

	private function renderJSON()
	{
		$json = array(
			'fields' => $this->jsonFields(), 
		);
		return new GWF_Response($json);
	}
	
	private function jsonFields()
	{
		$json = [];
		foreach ($this->fields as $field)
		{
			$json[] = $field->renderJSON(); 
		}
		return $json;
	}
	
	##############
	### Upload ###
	##############
	public function flowUpload(string $flowField)
	{
		$this->getField($flowField)->flowUpload();
	}
	
	public function cleanup()
	{
		foreach ($this->fields as $field)
		{
			$field->cleanup();
		}
	}
}
