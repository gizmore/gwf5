<?php
final class GWF_CompleteUser extends GWF_Method
{
	public function execute()
	{
		$q = GDO::escapeS(Common::getRequestString('query'));
		$condition = sprintf('user_name LIKE \'%%%1$s%%\' OR user_real_name LIKE \'%%%1$s%%\' OR user_guest_name LIKE \'%%%1$s%%\'', $q);
		$result = GWF_User::table()->select('*')->where($condition)->exec();
		$response = [];
		$cell = GDO_User::make();
		while ($user = $result->fetchObject())
		{
			$response[] = array(
				'id' => $user->getID(),
				'text' => $user->displayName(),
				'display' => $cell->gdo($user)->value($user->getID())->renderCell(),
			);
		}
		die(json_encode($response));
	}
}
