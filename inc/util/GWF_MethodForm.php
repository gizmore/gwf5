<?php
/**
 * Abstract method for a simple form.
 * Override "createForm" and "formValidated".
 * Add GDOTypes to your GWF_form
 * 
 * @author gizmore
 * @since 5.0
 * 
 * @see GDOType
 * @see GWF_Form
 * @see GWF_Method
 * @see GWF_MethodTable
 */
abstract class GWF_MethodForm extends GWF_Method
{
	/**
	 * @var GWF_Form
	 */
	protected $form;
	
	public function execute()
	{
		$this->form = $this->createForm();
		if (isset($_REQUEST['submit']))
		{
			if ($this->form->validate())
			{
				return $this->formValidated($this->form);
			}
			else
			{
				return $this->formInvalid($this->form);
			}
		}
		return $this->form->render();
	}
	
	################
	### Abstract ###
	################
	/**
	 * @return GWF_Form
	 */
	public abstract function createForm();
	
	/**
	 * @param GWF_Form $form
	 * @return GWF_Response
	 */
	public function formValidated(GWF_Form $form)
	{
		return $this->message('msg_form_saved')->add($this->form->render());
	}
	
	/**
	 * @param GWF_Form $form
	 * @return GWF_Response
	 */
	public function formInvalid(GWF_Form $form)
	{
		return $this->error('err_form_invalid')->add($this->form->render());
	}
}