<?php
/**
 * New implementation of GWF_Form based on GDOType5
 * 
 * @author gizmore
 * @version 5.0
 */
class GWF_Form
{
	/**
	 * 
	 * @var GDOType[]
	 */
	private $fields;
	
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
		$this->fields = array();
		$this->title = null;
		$this->action = $_SERVER['REQUEST_URI'];
		$this->method = 'post';
		$this->enctype = 'application/x-www-form-urlencoded';
	}
	
	public function addField(GDOType $field)
	{
		$this->fields[] = $field;
		return $this;
	}
	
	/**
	 * Get fields
	 * @return GDOType[]
	 */
	public function getFields()
	{
		return $this->fields;
	}
	
	public function title(string $key, array $args=null)
	{
		$this->title = GWF_Trans::t($key, $args);
		return $this;
	}
	
	public function info(string $key, array $args=null)
	{
		$this->info = GWF_Trans::t($key, $args);
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
			if (!$field->formValidate($this))
			{
				if (!$field->error)
				{
					$field->error('err_field_invalid', [$field->displayLabel()]);
				}
				$valid = false;
			}
		}
		return $valid;
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
	
	public function addValue(string $key, $value)
	{
		if (!$this->values)
		{
			$this->values = array();
		}
		$this->values[$key] = $value;
		return $this;
	}
	
	##############
	### Render ###
	##############
	public function render()
	{
		switch (Common::getRequestString('fmt', 'html'))
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
		return GWF_Template::templateMain('form.php', $tVars);
	}

	private function renderJSON()
	{
		$json = array(
			'fields' => $this->jsonFields(), 
		);
		return json_encode($json);
	}
	
	private function jsonFields()
	{
		$json = array();
		foreach ($this->fields as $field)
		{
			$json[] = $field->renderJSON(); 
		}
		return $json;
	}
	
}
