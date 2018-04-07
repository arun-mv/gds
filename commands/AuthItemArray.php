<?php
return [
	'permissions'=>[
		[
			'name'=>'generalsettings',
			'description'=>'Change general settings',
			'bizRule'=>null,
			'data'=>null,
		],

	],
'roles'=>[
		[
			'name'=>'admin',
			'description'=>'Administrator',
			'bizRule'=>null,
			'data'=>null,
			'children'=>[
					'changesettings',
					'changepassword',
				],
		],
	],

];
