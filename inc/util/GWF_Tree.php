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
	public function gdoAbstract() { return $this->gdoClassName() === 'GWF_Tree'; }
	public function gdoColumns()
	{
		$pre = $this->gdoTreePrefix();
		return array(
			GDO_Object::make($pre.'_parent')->klass(self::gdoClassNameS()),
			GDO_Int::make($pre.'_depth')->unsigned()->bytes(1),
			GDO_Int::make($pre.'_left')->unsigned(),
			GDO_Int::make($pre.'_right')->unsigned(),
		);
	}
	public function getIDColumn() { return $this->gdoPrimaryKeyColumn()->identifier(); }
	public function getParentColumn() { return $this->gdoTreePrefix().'_parent'; }
	public function getParentID() { return $this->getVar($this->getParentColumn()); }
	public function getParent() { return $this->getValue($this->getParentID()); }
	
	public function getDepthColumn() { return $this->gdoTreePrefix().'_depth'; }
	public function getDepth() { return $this->getVar($this->getDepthColumn()); }
	
	public function getLeftColumn() { return $this->gdoTreePrefix().'_left'; }
	public function getLeft() { return $this->getVar($this->getLeftColumn()); }

	public function getRightColumn() { return $this->gdoTreePrefix().'_right'; }
	public function getRight() { return $this->getVar($this->getRightColumn()); }

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
		return $this->rebuildTree(1, 1, 1);
	}

	private function rebuildTree($parent, $left, $depth)
	{
// 		$parent = (int)$parent;
// 		$left = (int)$left;
		$right = $left + 1;

		$p = $this->getParentColumn();
		$idc = $this->getIDColumn();

		$result = $this->table()->select($idc)->where("$p=$parent")->exec()->fetchAllValues();
		foreach ($result as $id)
		{
			$right = $this->rebuildTree($id, $right, $depth+1);
		}

		$l = $this->getLeftColumn();
		$r = $this->getRightColumn();
		$d = $this->getDepthColumn();
		$this->table()->update()->set("$l=$left, $r=$right, $d=$depth")->where("$idc=$parent")->debug()->exec();
		
		return $right+1;  
	}
}
