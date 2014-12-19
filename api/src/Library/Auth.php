<?php
namespace Library;

class Auth {
/**
     * Validating user api key
     * If the api key is there in db, it is a valid key
     * @param String $api_key user api key
     * @return boolean
     */
    public function isValidApiKey($api_key) {
        $sql = "SELECT * FROM app WHERE api_key = :api_key LIMIT 1";	
	    $query = \Library\Db::getInstance()->prepare($sql);
		$values = array(':api_key' => $api_key);
	    $query->execute($values);
		$result = $query->fetch(\PDO::FETCH_ASSOC);
		if($result != NULL)
			return true;
		else
			return false;
    }
	
	/**
     * Fetching user id by api key
     * @param String $api_key user api key
     */
    public function getAppId($api_key) {
        $sql = "SELECT app_id FROM app WHERE api_key = :api_key LIMIT 1";	
	    $query = \Library\Db::getInstance()->prepare($sql);
		$values = array(':api_key' => $api_key);
	    $query->execute($values);
		$result = $query->fetch(\PDO::FETCH_ASSOC);
		if($result != NULL)
			return $result["app_id"];
		else
			return NULL;
    }
}
?>