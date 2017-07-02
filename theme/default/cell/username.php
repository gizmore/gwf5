<?php $user instanceof GWF_User;
$id = $user->getID();
# Beautify markup and typography
# As it's a template you might want to add colors for permissions, a link to your profile or whatever.
if ($realname = $user->getRealName())
{
	printf('<gwf-user id="%s" nickname="%s">\'%s\'</gwf-user>', $id, $user->getName(), htmlspecialchars($realname));
}
elseif ($guestname = $user->getGuestName())
{
	printf('<gwf-user id="%s" nickname="~GUEST~">~%s~</gwf-user>', $id, htmlspecialchars($guestname));
}
else 
{
	printf('<gwf-user id="%s" nickname="%s">%2$s</gwf-user>', $id, $user->getName());
}
