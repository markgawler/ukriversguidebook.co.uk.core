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
	
	
	public function onUserAfterLogin($options)
	{	

		$email = $options['user']->email;
		
		$sdk = new Aws\Sdk([
				'region'   => 'eu-west-1',
				'version'  => 'latest'
		]);
		
		$dynamodb = $sdk->createDynamoDb();
		
		$response = $dynamodb->query([
				'TableName' => 'emailBounce',
				'KeyConditionExpression' => 'RecipientsEmail = :v_id',
				'ExpressionAttributeValues' =>  [
						':v_id' => ['S' => $email]
				]
		]);

		if (count($response['Items']) != 0) 
		{
			$session = JFactory::getSession();
			$session->set( 'ukrgbUpdateEmail', True );
		}
		return true;
	}
	
	/**
	*$oldUser - An associative array of the columns in the user table (current values).
	* $isnew - Boolean to identify if this is a new user (true - insert) or an existing one (false - update)
	* $newUser - An associative array of the columns in the user table (new values).
	**/
	public function onUserBeforeSave($oldUser, $isnew, $newUser)
	{
		// Make sure the email is updated if a bounce has been detected.
		$session = JFactory::getSession();
		if ($session->get('ukrgbUpdateEmail')){
			if ($newUser['email'] == $oldUser['email'])
			{
				$app = JFactory::getApplication();
				$app->enqueueMessage(JText::_('PLG_USER_UKRGB_EMAIL_NOT_CHANGED'));
				return false;
			}
			else {
				$sdk = new Aws\Sdk([
						'region'   => 'eu-west-1',
						'version'  => 'latest'
				]);
				
				$dynamodb = $sdk->createDynamoDb();
				
				$response = $dynamodb->updateItem([
						'TableName' => 'emailBounce',
						'Key' => [
								'RecipientsEmail' => [ 'S' => $oldUser['email']]
						],
						'ExpressionAttributeValues' =>  [
								':val1' => ['S' => $newUser['email']]
						] ,
    					'UpdateExpression' => 'set newEmail = :val1'
				]);
				
				
				$session->set( 'ukrgbUpdateEmail', False );
			}
		}		
		
		return true;
	}
}
