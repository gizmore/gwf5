<?php
/**
 * Auto completion for GDO_User types.
 * @author gizmore
 * @version 5.0
 * @since 5.0
 */
final class GWF_CompleteUser extends GWF_Method
{
	public function execute()
	{
		$q = GDO::escapeS(Common::getRequestString('query'));
		$condition = sprintf('user_name LIKE \'%%%1$s%%\' OR user_real_name LIKE \'%%%1$s%%\' OR user_guest_name LIKE \'%%%1$s%%\'', $q);
		$result = GWF_User::table()->select('*')->where($condition)->exec();
		$response = [];
		$cell = GDO_User::make('user_id');
		while ($user = $result->fetchObject())
		{
			$response[] = array(
				'id' => $user->getID(),
				'text' => $user->displayName(),
				'display' => $cell->gdo($user)->value($user->getID())->renderChoice(),
			);
		}
		die(json_encode($response));
	}
}
