<?php

namespace justcoded\yii2\rbac\models;

class Item
{
	const ROLE_GUEST = 'Guest';
	const ROLE_AUTHENTICATED = 'Authenticated';
	const ROLE_ADMIN = 'Administrator';
	const ROLE_MASTER = 'Master';

	const PERMISSION_ADMINISTER = 'administer';
	const PERMISSION_MASTER = '*';
}
