<?php
namespace app\commands;

use app\models\User;
use app\models\Admin;
use yii\console\Controller;


/**
 * This command is used to create a user with admin privilage.
 *
 * You can set the The password And user name for the admin [username] and [password]
 * 
 */
 
 class AdminInitController extends Controller
 {
 	//username
 	const USER_NAME = 'admin';
 	//password
 	const PASSWORD = 'admin123';
 	
 	public function actionIndex()
 	{
 		if($this->isInitiated())
 		{
 			echo"\n\nAlready Initialised\n\n";
 			exit(0);
 		}
 		$user = new User();
 		$user->scenario = User::SCENARIO_INIT;
 		$user->username = self::USER_NAME;
 		$user->setPassword(self::PASSWORD);
 		$user->generateAuthKey();
 		$user->status = User::STATUS_ACTIVE;
 		$user->type = User::USER_TYPE_ADMIN;
 		$user->email = 'info@etuwa.in';
 		if($user->save())
 		{
 			$admin = new Admin;
 			$admin->user_id = $user->id;
 			if(!$admin->save(false))
 			{
 				$user->delete();
 				echo "\n\nfailed !\n\n";
 			}
 			else
 			{
 				echo "\n\nSuccess..!\n\n";
 			}
 		}
 	}
 	
 	function isInitiated()
 	{
 		$user = User::findIdentity(1);
 		if(empty($user))
 			return false;
 		return true;
 	}
 }
