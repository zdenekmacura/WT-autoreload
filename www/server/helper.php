<?php


class BrrrHelper
{
    
    public static function createAccount($fullname, $email, $password) {

	$db = JFactory::getDbo();
    $query = $db->getQuery(true);

	$columns = array('reg_date', 'fullname', 'email', 'password');
 	$values = array('now()',$db->quote($fullname), $db->quote($email), $db->quote($password));
	$query
    ->insert($db->quoteName('brrr_login'))
    ->columns($db->quoteName($columns))
    ->values(implode(',', $values));

	try {
	$db->setQuery($query);
	$result = $db->execute();
	}
	catch (Exception $e) {
    return $e;
	}
    $addressID = $db->insertid();
    return $addressID;
}

   public static function loadPage ($pageid) {
	$db = JFactory::getDbo();
	$query = $db->getQuery(true)
            ->select(array('id','html','script','css'))
            ->from($db->quoteName('pages'))
            ->where('id = ' . $db->Quote($pageid));
	$db->setQuery($query);
	$result = $db->loadObject();
	return $result;
   }


	public static function getMaxUserId() 
    {
    // Obtain a database connection
	$db = JFactory::getDbo();
	// Retrieve the shout
	$query = $db->getQuery(true)
            ->select('max(id)')
            ->from($db->quoteName('brrr_login'));
	// Prepare the query
	$db->setQuery($query);
	// Load the row.
	$result = $db->loadResult();
	// Return the Hello
	return $result;
	}

	public static function checkLogin( $email , $password ) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
				->select(array('id','fullname','email','password'))
				->from($db->quoteName('brrr_login'))
				->where('email=' . $db->Quote($email) . ' and password=' . $db->Quote($password) );
		$db->setQuery($query);
		return $db->loadAssocList();
	}

   public static function getTSByUserId ($userid) {
   	 // Obtain a database connection
	$db = JFactory::getDbo();
	// Retrieve the shout
     // Obtain a database connection

	$query = $db->getQuery(true)
            ->select(array('teplotni_cidla.ssid','teplotni_cidla_mista.location'))
            ->from($db->quoteName('teplotni_cidla'))
            ->leftJoin('teplotni_cidla_mista ON (teplotni_cidla.ssid = teplotni_cidla_mista.ssid)')
             ->where('teplotni_cidla.userid = ' . $db->Quote($userid));
	$db->setQuery($query);
	$result = $db->loadObjectList();
	return $result;
   }


    	
    public static function getPassword($param) 
    {
    // Obtain a database connection
	$db = JFactory::getDbo();
	// Retrieve the shout
	$query = $db->getQuery(true)
            ->select($db->quoteName('password'))
            ->from($db->quoteName('teplotni-cidla'))
            ->where('ssid = ' . $db->Quote('TS04'));
	// Prepare the query
	$db->setQuery($query);
	// Load the row.
	$result = $db->loadObjectList();
	// Return the Hello
	return $result;
	}
	public static function getUserID($param)
	{
	$user =& JFactory::getUser();
	if($user->id) {
    $userid = $user->get('id'); }
	else {
    $userid = 0;
	}
	return $userid;
	}
	
	public static function getWifiID($param)
	{
	$input = new JInput;
	$post = $input->getArray($_POST);
	$wifiid = $post["wifiid"];
	return $wifiid;
	}

	public static function getGetArray() 
	{
	$input = new JInput;
	$post = $input->getArray($_GET);
	return $post;
	}
	
	public static function getPostArray() 
	{
	$input = new JInput;
	$post = $input->getArray($_POST);
	return $post;
	}
	public static function setWifiID($params,$userid,$wifiid)
	{
	$db = JFactory::getDbo();
    $query = $db->getQuery(true);
 	// Fields to update.

 	
 	$fields = array(
 			$db->quoteName('userid') . ' = ' . $userid,
 			$db->quoteName('date_registered') . ' =  now()'
 			);
    $query->update($db->quoteName('teplotni-cidla'))->set($fields)->where('ssid = ' . $db->Quote($wifiid));
    $db->setQuery($query);
    $result = $db->execute();
    $rows = $db->getAffectedRows();
	return $rows;
	}
	public static function getRegisteredWT($userid)
	{
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->select(array('ssid', 'userid', 'date_registered'));
	$query->from($db->quoteName('teplotni-cidla'));
	$query->where($db->quoteName('userid') . " = " . $db->quote($userid));
	$query->order('date_registered ASC');
	$db->setQuery($query);
	$results = $db->loadObjectList();
	return $results;
	}
	
    public static function getWifiIdLocal($wifiid)
	{
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->select(array('ssid', 'location', 'wifiid_loc','wifipass_loc'));
	$query->from($db->quoteName('teplotni-cidla-mista'));
	$query->where('ssid = ' . $db->Quote($wifiid));
	$query->order('date_located ASC');
	$db->setQuery($query);
	$results = $db->loadObject();
	return $results;
	}
	
	public static function setWifiSetup($post,$wifiid)
	{
	$db = JFactory::getDbo();
    $query = $db->getQuery(true);

	$columns = array('ssid', 'location', 'wifiid_loc', 'wifipass_loc', 'date_located');
 
	$values = array($db->quote($wifiid), $db->quote($post["wifi-location"]), $db->quote($post["wifiid-local"]), $db->quote($post["wifipass-local"]),'now()');
	$query
    ->insert($db->quoteName('teplotni-cidla-mista'))
    ->columns($db->quoteName($columns))
    ->values(implode(',', $values));
	try {
	$db->setQuery($query);
	$result = $db->execute();
	}
	catch (Exception $e){
    	echo $e->getMessage();
	}
	echo "result:" . $result . "resultend";
	return $result;
	}
}
?>