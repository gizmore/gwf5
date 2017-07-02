<?php
trait GDO_ObjectTrait
{
	public $completionURL;
	public $cascade = 'CASCADE';
	public $fkOn;
	public $klass;
	public $table;
	
	#########################
	### Object Completion ###
	#########################
	public function completion(string $completionURL)
	{
		$this->completionURL = $completionURL . '&ajax=1&fmt=json';
		return $this;
	}
	
	public function initCompletionJSON()
	{
		$gdo = $this->getGDOValue();
		return json_encode([
				'url' => $this->completionURL,
				'id' => $this->value,
				'value' => $gdo ? $gdo->renderChoice() : '',
		]);
	}
	
	###############
	### Cascade ###
	###############
	public function cascadeNull()
	{
		$this->cascade = 'SET NULL';
		return $this;
	}
	
	/**
	 * {@inheritDoc}
	 * @see GDOType::getGDOValue()
	 * @return GDO
	 */
	public function getGDOValue()
	{
		$id = $this->gdo ? $this->gdo->getVar($this->name) : $this->formValue();
		return $this->foreignTable()->find($id, false);
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
	
	public function getGDOData()
	{
		return array($this->name => $this->gdo->getVar($this->name));
	}
	
	#####################
	### Class to join ###
	#####################
	public function klass(string $klass, GDO $table=null)
	{
		$this->klass = $klass;
		$this->table = $table ? $table : GDO::tableFor($klass);
		return $this;
	}
	
	public function table(GDO $table=null)
	{
		return $table ? $this->klass($table->gdoClassName(), $table) : $this;
	}
	
	########################
	### Custom ON clause ###
	########################
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
		return $this->table ? $this->table : GDO::tableFor($this->klass); # @TODO: WTF. $this->table gets emptied for unknown reason!
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
		$define = preg_replace('#,FOREIGN KEY .* ON UPDATE CASCADE#', '', $define);
		$on = $this->fkOn ? $this->fkOn : $primaryKey->identifier();
		return "$define{$this->gdoNullDefine()}".
				",FOREIGN KEY ({$this->identifier()}) REFERENCES $tableName($on) ON DELETE {$this->cascade} ON UPDATE CASCADE";
	}
}