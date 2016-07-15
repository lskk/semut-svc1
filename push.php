<?php
/**
 * @file
 * sample_push.php
 *
 * Push demo
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://code.google.com/p/apns-php/wiki/License
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to aldo.armiento@gmail.com so we can send you a copy immediately.
 *
 * @author (C) 2010 Aldo Armiento (aldo.armiento@gmail.com)
 * @version $Id: sample_push.php 65 2010-12-13 18:38:39Z aldo.armiento $
 */

// Adjust to your timezone
date_default_timezone_set('Europe/Rome');

if(isset($_POST['submit'])){
	// Report all PHP errors
	echo "<pre>";
	error_reporting(-1);
	
	// Using Autoload all classes are loaded on-demand
	require_once 'ApnsPHP/Autoload.php';
	
	// Instanciate a new ApnsPHP_Push object
	$push = new ApnsPHP_Push(
		ApnsPHP_Abstract::ENVIRONMENT_SANDBOX,
		'server_certificates_bundle_sandbox.pem'
	);
	
	// Set the Root Certificate Autority to verify the Apple remote peer
	$push->setRootCertificationAuthority('entrust.pem');
	
	// Connect to the Apple Push Notification Service
	$push->connect();
	
	// Instantiate a new Message with a single recipient
	$tujuan = $_POST['tujuan'];
	$tujuan = str_replace(" ",'',$tujuan);
	$message = new ApnsPHP_Message($tujuan);
	
	// Set a custom identifier. To get back this identifier use the getCustomIdentifier() method
	// over a ApnsPHP_Message object retrieved with the getErrors() message.
	$message->setCustomIdentifier("Message-Badge-3");
	
	// Set badge icon to "3"
	$message->setBadge(intval($_POST['badge']));
	
	// Set a simple welcome text
	$message->setText($_POST['pesan']);
	
	// Play the default sound
	$message->setSound();
	
	// Set a custom property
	if(isset($_POST['property'])){
		$message->setCustomProperty($_POST['property'], json_decode("$_POST[property_content]"));
	}
	
	// Set another custom property
	//$message->setCustomProperty('acme3', array('bing', 'bong'));
	
	// Set the expiry value to 30 seconds
	$message->setExpiry(30);
	
	// Add the message to the message queue
	$push->add($message);
	
	// Send all messages in the message queue
	$push->send();
	
	// Disconnect from the Apple Push Notification Service
	$push->disconnect();
	
	// Examine the error message container
	$aErrorQueue = $push->getErrors();
	if (!empty($aErrorQueue)) {
		var_dump($aErrorQueue);
	}
	echo "</pre>";
}
?>

<form method="post" action="push.php">
<table>
<tr>
    <td>Tujuan : </td>
    <td><input type="text" name="tujuan" value="<?= $tujuan?>" style="width:200px" /></td>
</tr>
<tr>
	<td>Pesan : </td>
    <td><textarea name="pesan" style="width:200px"></textarea></td>
</tr>
<tr>
	<td>Badge Number : </td>
    <td><textarea name="badge" style="width:200px"></textarea></td>
</tr>
<tr>
	<td>Custom Property Name : </td>
    <td><input type="text" name="property" style="width:200px" /></td>
</tr>
<tr>
	<td>Custom Property Content : </td>
    <td><textarea name="property_content" style="width:200px" ></textarea></td>
</tr>
<tr>
	<td colspan="2" align="right">
	<input type="submit" name="submit" />
    </td>
</tr>
</form>