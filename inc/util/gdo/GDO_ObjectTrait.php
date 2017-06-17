<?php
trait GDO_ObjectTrait
{
	/**
	 * {@inheritDoc}
	 * @see GDOType::getGDOValue()
	 * @return GDO
	 */
	public function getGDOValue()
	{
		return $this->foreignTable()->find($this->gdo->getVar($this->name), false);
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see GDOType::setGDOValue()
	 */
	public function setGDOValue($value)
	{
		$this->gdo->setVar($this->name, $value ? $value->getID() : null);
	}
	
	#####################
	### Class to join ###
	#####################
	public $klass;
	public function klass($klass)
	{
		$this->klass = $klass;
		return $this;
	}
	
	########################
	### Custom ON clause ###
	########################
	public $fkOn;
	public function fkOn(string $on)
	{
		$this->fkOn = $on;
		return $this;
	}
	
	#################
	### DB Column ###
	#################
	/**
	 * @return GDO
	 */
	public function foreignTable()
	{
		return GDODB::tableS($this->klass);
	}
	
	/**
	 * @return GDOQuery
	 */
	public function foreignQuery()
	{
		$table = $this->foreignTable();
		return $table->query()->from($table->gdoTableIdentifier());
	}
	
	/**
	 * Take the foreign key primary key definition and str_replace to convert to foreign key definition.
	 *
	 * {@inheritDoc}
	 * @see GDOType::gdoColumnDefine()
	 */
	public function gdoColumnDefine()
	{
		$table = $this->foreignTable();
		$tableName = $table->gdoTableIdentifier();
		if (!($primaryKey = $table->gdoPrimaryKeyColumn()))
		{
			throw new GWF_Exception('err_gdo_no_primary_key', [$tableName, $this->identifier()]);
		}
		$define = $primaryKey->gdoColumnDefine();
		$define = str_replace($primaryKey->identifier(), $this->identifier(), $define);
		$define = str_replace(' NOT NULL', '', $define);
		$define = str_replace(' PRIMARY KEY', '', $define);
		$define = str_replace(' AUTO_INCREMENT', '', $define);
		$on = $this->fkOn ? $this->fkOn : ($primaryKey->identifier());
		return "$define{$this->gdoNullDefine()}".
				",FOREIGN KEY ({$this->identifier()}) REFERENCES $tableName($on) ON DELETE CASCADE ON UPDATE CASCADE";
	}
}