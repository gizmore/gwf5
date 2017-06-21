<?php
/**
 * Column type that references another table/GDO
 * 
 * @author gizmore
 * @since 5.0
 * @see GDO_Primary
 */
class GDO_Object extends GDOType
{
	use GDO_ObjectTrait;
	
	private $completionURL;
	public function completion(string $completionURL)
	{
		$this->completionURL = $completionURL;
		return $this;
	}
	
	public function initCompletionJSON()
	{
		$gdo = $this->getGDOValue();
		return json_encode([
			'url' => $this->completionURL,
			'id' => $this->value,
			'value' => $gdo ? $gdo->displayName() : '',
		]);
	}
	
	public function render()
	{
		if ($this->completionURL)
		{
			return GWF_Template::mainPHP('form/object_completion.php', ['field'=>$this]);
		}
		else
		{
			return GWF_Template::mainPHP('form/object.php', ['field'=>$this]);
		}
	}
	
	
}
