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
	public function isTransactional() { return true; }
	
	/**
	 * @var GWF_Form
	 */
	protected $form;
	
	public function execute()
	{
		$this->form = $this->getForm();
		
		if ($flowField = Common::getRequestString('flowField'))
		{
			return $this->form->flowUpload($flowField);
		}
		
		if (isset($_REQUEST['submit']))
		{
			if ($this->form->validate())
			{
				$response = $this->formValidated($this->form);
			}
			else
			{
				$response = $this->formInvalid($this->form);
			}
		}
		else
		{
			$response = $this->renderPage();
		}
		
		$this->form->cleanup();
		
		return $response;
	}
	
	public function title(string $key, array $args=null)
	{
		$this->getForm()->title($key, $args);
		return parent::title($key, $args);
	}
	
	/**
	 * @return GWF_Response
	 */
	public function renderPage()
	{
		return $this->form->render();
	}
	
	/**
	 * @return GWF_Form
	 */
	public function getForm()
	{
		if (!$this->form)
		{
			$this->form = new GWF_Form();
			$this->title('ft_'.strtolower(get_called_class()));
			$this->createForm($this->form);
		}
		return $this->form;
	}
	
	################
	### Abstract ###
	################
	/**
	 * @return GWF_Form
	 */
	public abstract function createForm(GWF_Form $form);
	
	/**
	 * @param GWF_Form $form
	 * @return GWF_Response
	 */
	public function formValidated(GWF_Form $form)
	{
		return $this->message('msg_form_saved')->add($this->renderPage());
	}
	
	/**
	 * @param GWF_Form $form
	 * @return GWF_Response
	 */
	public function formInvalid(GWF_Form $form)
	{
		return $this->error('err_form_invalid')->add($this->renderPage());
	}
	
	
	public function executeWebsocket(GWS_Message $msg)
	{
		$this->getForm()->withWSValuesFrom($msg);
	}
}
