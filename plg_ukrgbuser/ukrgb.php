<?php
/**
 * @package		Joomla
 * @subpackage	User
 * @copyright	Copyright (C) 2015 Mark Gawler. All rights reserved.
 * @link		http://www.ukriversguidebook.co.uk
 * @license		License GNU General Public License version 2 or later
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class plgUserUkrgb extends JPlugin {
	/**
	 * Plugin to prompt user to update email address 
	 *
	 */
	protected $autoloadLanguage = true;
	
	/**
	 * On Failed login inform user that usernames are case sensative if the username exists
	 * @param unknown $user
	 * @param string $options
	 */
	public function onUserLoginFailure($user, $options=null)
	{
		$username = strtolower($user['username']);
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
		->select($db->quoteName(array('name')))
		->from($db->quoteName('#__users'))
		->where($db->quoteName('username') . ' LIKE '. $db->quote($username));
		$db->setQuery($query);
		$result = $db->loadResult();
		// only display the warning if the username is found in the DB and dose not match the caseing 
		// of the entered name, ie. dont display for invalid password and invlid password.
		if ($result and $user['username'] != $result)
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('PLG_USER_UKRGB_CASE_SENSITIVE_USER') .'<b>"'. $result .'"</b>'. JText::_('PLG_USER_UKRGB_CASE_SENSITIVE_USER_PF'));
		}
	}
	
	/**
	 * After login check to se if the users email has bounced, notify user if that is the case
	 * @param unknown $options
	 * @return boolean
	 */
	public function onUserAfterLogin($options)
	{	
		$email = $options['user']->email;
		
		$dynamodb = $this->getDBClient();
		
		$response = $dynamodb->query([
				'TableName' => 'emailBounce',
				'KeyConditionExpression' => 'RecipientsEmail = :v_id',
				'ExpressionAttributeValues' =>  [':v_id' => ['S' => $email]]
		]);

		if (count($response['Items']) != 0) 
		{
			$session = JFactory::getSession();
			$session->set( 'ukrgbUpdateEmail', True );
		}
		return true;
	}
	

	/**
	 * Dont let the user save there profile with an email that has bounced.
	 * 
	 * @param unknown $oldUser - An associative array of the columns in the user table (current values).
	 * @param unknown $isnew   - Boolean to identify if this is a new user (true - insert) or an existing one (false - update)
	 * @param unknown $newUser - An associative array of the columns in the user table (new values).
	 */
	public function onUserBeforeSave($oldUser, $isnew, $newUser)
	{
		// Make sure the email is updated if a bounce has been detected.
		$session = JFactory::getSession();
		if ($session->get('ukrgbUpdateEmail')){
			if ($newUser['email'] == $oldUser['email'])
			{
				$app = JFactory::getApplication();
				$app->enqueueMessage(JText::_('PLG_USER_UKRGB_EMAIL_NOT_CHANGED'). '<a href="mailto:admin@ukriversguidebook.co.uk">Email Admin</a>');
				return false;
			}
			else {
				$dynamodb = $this->getDBClient();
				
				$response = $dynamodb->updateItem([
						'TableName' => 'emailBounce',
						'Key' => [
								'RecipientsEmail' => [ 'S' => $oldUser['email']]
						],
						'ExpressionAttributeValues' =>  [
								':val1' => ['S' => $newUser['email']],
								':val2' => ['S' => JFactory::getDate()->toSql()]
						] ,
    					'UpdateExpression' => 'set newEmail = :val1, updateDate = :val2'
				]);
				
				$session->set( 'ukrgbUpdateEmail', False );
			}
		}		
		
		return true;
	}
	
	private function getDBClient (){
		$sdk = new Aws\Sdk([
				'region'   => 'eu-west-1',
				'version'  => 'latest'
		]);
		
		$dynamodb = $sdk->createDynamoDb();
		
		return $dynamodb;
	}
}
