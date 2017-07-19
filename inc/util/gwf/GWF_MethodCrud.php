<?php
/**
 * Abstract CReate|Update|Delete for a GDO.
 * @author gizmore
 * @since 5.0
 */
abstract class GWF_MethodCrud extends GWF_MethodForm
{
	/**
	 * @return GDO
	 */
	public abstract function gdoTable();
	
	public abstract function hrefList();
	
	public function isUserRequired() { return true; }
	
	public function canCreate(GDO $table) { return true; }
	public function canUpdate(GDO $gdo) { return true; }
	public function canDelete(GDO $gdo) { return true; }
	
	public function afterCreate(GWF_Form $form) {}
	public function afterUpdate(GWF_Form $form) {}
	public function afterDelete(GWF_Form $form) {}
	
	public function getCRUDID() { return Common::getRequestString('id'); }
	
	/**
	 * @var GDO
	 */
	protected $gdo;
	
	public function execute()
	{
		$table = $this->gdoTable();
		if ($id = $this->getCRUDID())
		{
			$this->gdo = $table->find($id);
			if (!$this->canUpdate($this->gdo))
			{
				throw new GWF_Exception('err_permission_update');
			}
		}
		elseif (!$this->canCreate($table))
		{
			throw new GWF_Exception('err_permission_create');
		}
		
		return parent::execute();
	}
	
	public function createForm(GWF_Form $form)
	{
		$table = $this->gdoTable();
		foreach ($table->gdoColumnsCache() as $gdoType)
		{
			if ($gdoType->editable)
			{
				$form->addField($gdoType);
			}
		}
		$this->createFormButtons($form);
	}
	
	public function createFormButtons(GWF_Form $form)
	{
		$form->addFields(array(
			GDO_Submit::make(),
			GDO_AntiCSRF::make()
		));
		if ($this->gdo && $this->canDelete($this->gdo))
		{
			$form->addField(GDO_Submit::make('delete')->icon('delete'));
		}
		if ($this->gdo)
		{
			$form->withGDOValuesFrom($this->gdo);
		}
		
		if ($this->gdo)
		{
			$this->title('ft_crud_update', [$this->getSiteName(), $this->gdoTable()->gdoHumanName()]);
		}
		else
		{
			$this->title('ft_crud_create', [$this->getSiteName(), $this->gdoTable()->gdoHumanName()]);
		}
	}
	
	##############
	### Bridge ###
	##############
	public function formValidated(GWF_Form $form)
	{
		$table = $this->gdoTable();
		return $this->gdo ? $this->onUpdate($form) : $this->onCreate($form);
	}
	
	public function onSubmit_delete(GWF_Form $form)
	{
		if (!$this->canDelete($this->gdo))
		{
			throw new GWF_Exception('err_permission_delete');
		}
		return $this->onDelete($form);
	}
	
	###############
	### Actions ###
	###############
	public function onCreate(GWF_Form $form)
	{
		$table = $this->gdoTable();
		$gdo = $this->gdo = $table->blank($form->values())->insert();
		return
			$this->message('msg_crud_created', [$gdo->gdoClassName()])->
			add($this->afterCreate($form))->
			add(GWF_Website::redirectMessage($this->hrefList()));
	}
	
	public function onUpdate(GWF_Form $form)
	{
		$this->gdo->saveVars($form->values());
		return
			$this->message('msg_crud_updated', [$this->gdo->gdoClassName()])->
			add($this->afterUpdate($form))->
			add($this->renderForm());
	}
	
	public function onDelete(GWF_Form $form)
	{
		$this->gdo->delete();
		return $this->message('msg_crud_deleted', [$this->gdo->gdoClassName()])->
			add($this->afterDelete($form))->
			add(GWF_Website::redirectMessage($this->hrefList()));
	}
}
