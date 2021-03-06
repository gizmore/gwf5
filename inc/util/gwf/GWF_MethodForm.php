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
	
	public function isUserRequired() { return true; }
	
	/**
	 * @var GWF_Form
	 */
	protected $form;
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see GWF_Method::execute()
	 * @return GWF_Response
	 */
	public function execute()
	{
		return $this->renderForm();
	}
	
	public function title(string $key, array $args=null)
	{
		$this->getForm()->title($key, $args);
		return parent::title($key, $args);
	}
	
	public function renderForm()
	{
		$response = false;
		
		$form = $this->getForm();
		
		if ($flowField = Common::getRequestString('flowField'))
		{
			return $form->flowUpload($flowField);
		}
		
		foreach ($form->getFields() as $gdoType)
		{
			if ($gdoType instanceof GDO_Submit)
			{
				$name = $gdoType->name;
				if (isset($_REQUEST[$name]))
				{
					if ($form->validate())
					{
						$response = call_user_func([$this, "onSubmit_$name"], $form);
					}
					else
					{
						$response = $this->formInvalid($form)->add($this->renderPage());
					}
					break;
				}
			}
		}

		return $response ? $response : $this->renderPage();
	}
	
	public function onSubmit_submit(GWF_Form $form)
	{
		return $this->formValidated($form);
	}
	
	/**
	 * @return GWF_Response
	 */
	public function renderPage()
	{
		return $this->getForm()->render();
	}
	
	/**
	 * @return GWF_Form
	 */
	public function getForm()
	{
		if (!$this->form)
		{
			$this->form = new GWF_Form();
			$this->title('ft_'.strtolower(get_called_class()), [$this->getSiteName()]);
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
		return $this->message('msg_form_saved');
	}
	
	/**
	 * @param GWF_Form $form
	 * @return GWF_Response
	 */
	public function formInvalid(GWF_Form $form)
	{
		return $this->error('err_form_invalid');
	}
	
}
