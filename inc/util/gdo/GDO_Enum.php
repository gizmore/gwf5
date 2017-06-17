<?php
/**
 * Enums are an own type, but very similiar to select.
 * Selects, however, override the combobox and have a different database type.
 * Enums filter via a multiselect.
 * @author gizmore
 * @since 5.0
 */
class GDO_Enum extends GDOType
{
	use GDO_NameLabelTrait;
	
	public $enumValues;
	
	public function enumValues(string ...$enumValues)
	{
		$this->enumValues = $enumValues;
		return $this;
	}
	
	public function gdoColumnDefine()
	{
		$values = implode(',', array_map(array('GDO', 'quoteS'), $this->enumValues));
		return "{$this->identifier()} ENUM ($values){$this->gdoNullDefine()}{$this->gdoInitialDefine()}";
	}

	public function render()
	{
		return GWF_Template::mainPHP('form/enum.php', ['field' => $this]);
	}
	
	##############
	### Filter ###
	##############
	/**
	 * Render select filter header.
	 */
	public function renderFilter()
	{
		return GWF_Template::mainPHP('filter/enum.php', ['field' => $this]);
	}
	
	/**
	 * Filter value is an array.
	 */
	public function filterValue()
	{
		if ($filter = parent::filterValue())
		{
			if ($filter = json_decode($filter))
			{
				return $filter;
			}
		}
		return [];
	}
	
	/**
	 * Display for inline init.
	 */
	public function displayFilterValue()
	{
		return GWF_Javascript::jsonEncodeSingleQuote($this->filterValue());
	}

	/**
	 * Add where clause to query on filter.
	 */
	public function filterQuery(GDOQuery $query)
	{
		$filter = $this->filterValue();
		if (!empty($filter))
		{
			$query->where(sprintf('%s IN ("%s")', $this->identifier(), implode('","', $filter)));
		}
	}
	
}