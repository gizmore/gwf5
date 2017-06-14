<?php
/**
 * The auto inc column is unsigned and sets the primary key after insertions.
 * 
 * @author gizmore
 * @since 5.0
 * @see GDO_CreatedAt
 * @see GDO_CreatedBy
 * @see GDO_EditedAt
 * @see GDO_EditedBy
 */
class GDO_AutoInc extends GDO_Int
{
	###############
	### GDOType ###
	###############
	public function __construct()
	{
		$this->unsigned();
		$this->label('id');
	}
	
	public function name(string $name)
	{
		$this->name = $name;
		return $this;
	}
	
	##############
	### Column ###
	##############
	# Weird workaround for mysql primary key defs.
	public function primary() { return $this; }
	public function isPrimary() { return true; }
	public function gdoColumnDefine()
	{
		return "{$this->identifier()} INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY";
	}

	##############
	### Events ###
	##############
	public function gdoAfterCreate()
	{
		if ($id = GDODB::$INSTANCE->insertId())
		{
			$this->gdo->setVar($this->name, (string)$id, false);
		}
	}
}
