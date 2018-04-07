<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use yii\rbac\Item;
use app\models\Admin;

/*
* This command is used to manage Role Based Access Control (RBAC)
*
* All rbac items, itemchild , roles are defined in the AuthItemArray file
*
* yii2 rbac have PERMISSION and ROLES as items (tasks and operation are depricated)
*
* yii tasks and operations changed to permissions
*/

class RbacController extends Controller
{
	private $_authManager;
	
	public $interactive=true;
	
	//used to count the newly added items 
	public static $newCount=0;
	
	public static $c=0;
	
	private  $_authItemsArray = array();
	private  $_permissions = array();
	private  $_roles = array();
	
	
	public function getHelp()
	{
		
		$description = "DESCRIPTION\n";
		$description .= '    '."This command generates an initial RBAC authorization hierarchy.\n";
		return parent::getHelp() . $description;
	}
	
	/*
	* @ assign default roles to users
	*/
	public function actionAssignDefaultRoles()
	{
		// initialises authManager
		$this->setAuthManager();
		$admins=Admin::find()->all();
		$adminRole = $this->_authManager->createRole('admin');
		foreach($admins as $admin)
		{
			if(!$this->_authManager->getAssignment('admin',$admin->user_id))
			{
				$this->_authManager->assign($adminRole,$admin->user_id);
				self::$newCount++;
			}
		}
		$message = $this->ansiFormat("Success Total ".self::$newCount." user(s) granded privilages\n",Console::FG_GREEN);
		$this->stdout($message, Console::BOLD);
		return 0;
	}
	
		
	
	/*
	* @ creates PERMISSION and ROLES using AuthItemArray
	*/
	public function actionIndex()
	{
		// initialises authManager
		$this->setAuthManager();
				
		
		$this->_authItemsArray =require(dirname(__FILE__).'/AuthItemArray.php');
		$this->_permissions = $this->_authItemsArray['permissions'];
		$this->_roles = $this->_authItemsArray['roles'];
		
		// creates permissions
		foreach($this->_permissions as $permission)
		{
			$this->createItem($permission['name'],Item::TYPE_PERMISSION,$permission['description'],$permission['data']);			
		}
		/*
		foreach($authItemsArray['tasks'] as $arr)
		{
			 $item=$this->createIfNotExist($arr['name'],CAuthItem::TYPE_TASK,$arr['description'],$arr['bizRule'],$arr['data']);
			 if(isset($arr['children']) && !empty($arr['children']))
				$parentChildren[$arr['name']]=$arr['children'];
		}*/
		
		// creates roles
		foreach($this->_roles as $role)
		{
			 $this->createItem($role['name'],Item::TYPE_ROLE,$role['description'],$role['data']);
		}
		
		echo self::$newCount===0?"No new items found\n":"creating items completed.\n\n\n ";
		
		//now we have to add authItemChild of each role		
		foreach($this->_roles as $role)
		{
			$parent = $role['name'];
			if(!empty($role['children']))
			{
				$children = $role['children'];
				$this->addChildren($parent,$children);
			}
		}
		
		 //provide a message indicating success
		  $message = $this->ansiFormat("\n\nAuthorization hierarchy successfully generated. Total ".self::$newCount." item(s) added\n\n",Console::FG_GREEN);
		  $this->stdout($message, Console::BOLD);
		  return 0;
	
    }
    
    
    /*
     * @ initialise authManager
     */
    protected function setAuthManager()
    {
	//ensure that an authManager is defined as this is mandatory for creating an auth heirarchy
	if(($this->_authManager=Yii::$app->authManager)===null)
	{
	    $message = $this->ansiFormat("Error: an authorization manager, named 'authManager' must be con-figured to use this command.",Console::FG_RED);
	    $this->stdout($message, Console::BOLD);
	    return 1;
	}
    }
    
    
    /*
     * @ uses recursive calls to add hierarchy of children  
     */
    protected function addChildren($parent,$children)
    {
    	foreach($children as $child)
	{
		// checks whether this child have any children
		$grandChildren = $this->getChildren($child);
		if(!empty($grandChildren)){
			// permission have children so creating a recursive call
			$this->addChildren($child,$grandChildren);
		}
		
		$parentItem = $this->_authManager->createPermission($parent);
		$childItem = $this->_authManager->createPermission($child);
		// checks if already added
		if(!$this->_authManager->hasChild($parentItem,$childItem)){
			echo 'Assigning child '.$child.' to '.$parent,"\n";
			$this->_authManager->addChild($parentItem, $childItem);
		}
	}
    }
    
    
    /*
     * @ returns children of an parent if any 
     */
    protected function getChildren($parent)
    {
    	foreach($this->_permissions as $permission)
    	{
    		if($permission['name']=== $parent && !empty($permission['children']))
  		return $permission['children'];
    	}
    }
	
    protected function createItem($name,$type,$description='',$data=null)
    {
    	$time = time();
    	if($type == Item::TYPE_PERMISSION)
    	{
		$item=$this->_authManager->getPermission($name);
		if(empty($item))
		{
			$item = $this->_authManager->createPermission($name);
			$item->description = $description;
			$item->data = serialize($data);
			self::$newCount++;
			echo "creating new item $name \n";
			$item=$this->_authManager->add($item);
		}
	}
	elseif($type == Item::TYPE_ROLE)
	{
		$item=$this->_authManager->getRole($name);
		if(empty($item))
		{
			$item = $this->_authManager->createRole($name);
			$item->description = $description;
			$item->data = serialize($data);
			//$item->created_at = $time;
			self::$newCount++;
			echo "creating new item $name \n";
			$item=$this->_authManager->add($item);
		}
	}
	return $item;
    }		
	
}
