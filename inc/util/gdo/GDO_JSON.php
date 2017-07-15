<?php
final class GDO_JSON extends GDOType
{
	public function setGDOValue($value)
	{
		return $this->value($this->encodeJSON($value));
	}
	
	public function encodeJSON($object)
	{
		return json_encode($this->convertJSON($object));
	}
	
	public function convertJSON($object)
	{
		$json = array();
		if (is_array($object))
		{
			foreach ($object as $gdo)
			{
				if ($gdo instanceof GDO)
				{
					$json[] = $gdo->toJSON();
				}
				else
				{
					throw new GWF_Exception('err_json_encode_only_gdo');
				}
			}
		}
		elseif ($object instanceof GDO)
		{
			$json = $object->toJSON();
		}
		else
		{
			throw new GWF_Exception('err_json_encode_only_gdo');
		}
		return $json;
	}

	public function renderCell()
	{
		return $this->getValue();
	}
}
