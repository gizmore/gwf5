<?php
/**
 * Abstract Tree class stolen from http://articles.sitepoint.com/article/hierarchical-data-database/3
 * @author gizmore
 */
class GWF_Tree extends GDO
{
	public function gdoTreePrefix() { return 'tree'; }

	###########
	### GDO ###
	###########
	public function gdoAbstract() { return true; }
	public function gdoColumns()
	{
		$pre = $this->gdoTreePrefix();
		return array(
				GDO_Int::make($pre.'_parent')->unsigned(),
				GDO_Int::make($pre.'_left')->unsigned(),
				GDO_Int::make($pre.'_right')->unsigned(),
		);
	}
	public function getIDColumn() { return $this->gdoPrimaryKeyColumn()->identifier(); }
// 	public function getKeyColumn() { return $this->getColumnPrefix().'tree_key'; }
// 	public function getKey() { return $this->getVar($this->getKeyColumn()); }
	public function getLeftColumn() { return $this->gdoTreePrefix().'_left'; }
	public function getLeft() { return $this->getVar($this->getLeftColumn()); }
	public function getRightColumn() { return $this->gdoTreePrefix().'_right'; }
	public function getRight() { return $this->getVar($this->getRightColumn()); }
	public function getParentColumn() { return $this->gdoTreePrefix().'_parent'; }
	public function getParentID() { return $this->getVar($this->getParentColumn()); }

	public function getTree()
	{
		$pre = $this->getColumnPrefix();
		$left = $pre.'_left';
		$l = $this->getLeft();
		$r = $this->getRight();
		return $this->select('*')->where("$left BETWEEN $l AND $r")->order($left)->exec()->fetchAllObjects();
	}

	public function rebuildFullTree()
	{
		return $this->rebuildTree(0, 0);
	}

	private function rebuildTree($parent, $left)
	{
// 		$parent = (int)$parent;
// 		$left = (int)$left;
		$right = $left + 1;

		$p = $this->getParentColumn();
		$idc = $this->getIDColumn();

		$result = $this->table()->select($idc)->where("$p=$parent")->exec()->fetchAllValues();
		foreach ($result as $id)
		{
			$right = $this->rebuildTree($id, $right);
		}

		$l = $this->getLeftColumn();
		$r = $this->getRightColumn();
		$this->table()->update()->set("$l=$left, $r=$right")->where()->exec("$idc=$parent");
		
		return $right+1;  
	}
}
