<?php
abstract class GWF_MethodQuery extends GWF_Method
{
	/**
	 * @return GDOQuery
	 */
	public abstract function gdoQuery();

	/**
	 * @return GDOType[]
	 */
	public function gdoParameters()
	{
		return $this->gdoQuery()->table->gdoPrimaryKeyColumns();
	}

	/**
	 * @return GDOQuery
	 */
	public function gdoFilteredQuery()
	{
		$query = $this->gdoQuery();
		if ($filters = $this->gdoParameters())
		{
			foreach ($filters as $gdoType)
			{
				$gdoType->filterQuery($query);
			}
		}
		return $query;
	}

}
