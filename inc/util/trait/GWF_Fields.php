<?php
/**
 * A class that has GDOType fields.
 * @author gizmore
 * @since 5.0
 */
trait GWF_Fields
{
	/**
	 * Collection of GDOType fields.
	 * @var GDOType[]
	 */
	private $fields = [];
	
	/**
	 * @param GDOType $field
	 * @return self
	 */
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
	
	
	
}
