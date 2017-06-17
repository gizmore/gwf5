<?php
class GWF_UserActivation extends GDO
{
	public function gdoCached() { return false; }
	public function gdoColumns()
	{
		return array(
			GDO_AutoInc::make('ua_id'),
			GDO_Token::make('ua_token')->notNull(),
			GDO_CreatedAt::make('ua_time')->notNull(),

			# We copy these fields to user table
			GDO_Username::make('user_name')->notNull(),
			GDO_Password::make('user_password')->notNull(),
			GDO_Email::make('user_email'),
			GDO_IP::make('user_register_ip')->notNull(),
		);
	}
	
	public function getID() { return $this->getVar('ua_id'); }
	public function getToken() { return $this->getVar('ua_token'); }
	public function getEmail() { return $this->getVar('user_email'); }
	public function getUsername() { return $this->getVar('user_name'); }
	
	public function getHref() { return GWF_Url::relative("/index.php?mo=Register&me=Activate&id={$this->getID()}&token={$this->getToken()}"); }
	public function getUrl() { return GWF_Url::absolute($this->getHref()); }
}
