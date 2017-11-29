<?php
include($_SERVER['DOCUMENT_ROOT'].'/application/libraries/JWT/JWT.php');
use Firebase\JWT\JWT;
class ZoomAPI{
/*The API Key, Secret, & URL will be used in every function.*/
//private $api_key = 'E8OYGdEDSleFqd646QUZOw';
//private $api_secret = 'bcAxseuyNhe6fqRjfzNOoWhGW4rP1M483FbJ';
private $api_key = '2XUaAFKER-il3MkOCDwEuw';
private $api_secret = 'Ld0IJEkZsixnSFGhqXLf5yz1JbbfGfu0Cu6E';
private $access_token = 'jgQWCAjUiXFlKHX5IEVye960KfEpRGJsn0P5';
private $api_url = 'https://api.zoom.us/v2/';

/*Function to send HTTP POST Requests*/
/*Used by every function below to make HTTP POST call*/
function sendRequest($calledFunction, $data){
	/*Creates the endpoint URL*/
	$request_url = $this->api_url.$calledFunction;

	/*Adds the Key, Secret, & Datatype to the passed array*/
	$data['api_key'] = $this->api_key;
	$data['api_secret'] = $this->api_secret;
	$data['data_type'] = 'JSON';
    
	$postFields = http_build_query($data);
	/*Check to see queried fields*/
	/*Used for troubleshooting/debugging*/
	//echo $postFields;

	/*Preparing Query...*/
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_URL, $request_url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$response = curl_exec($ch);

	/*Check for any errors*/
	$errorMessage = curl_exec($ch);
	//echo $errorMessage;
	curl_close($ch);

	/*Will print back the response from the call*/
	/*Used for troubleshooting/debugging		*/
	//echo $request_url;
	//var_dump($data);
	//var_dump($response);
	if(!$response){
		return false;
	}
	/*Return the data in JSON format*/
	$jsonresponse=json_encode($response);
	$arrdata=json_decode($jsonresponse, true);
	//echo $arrdata;
	return $arrdata;
}
function createAUser($email){		
		$createAUserArray = array();
		$createAUserArray['email'] = $email;
		$createAUserArray['type'] = '1';
        $createAMeetingArray["access_token"] = $this->getToken();
		return $this->sendRequest('user/create', $createAUserArray);
	}   
	function autoCreateAUser($email, $password){
	  $autoCreateAUserArray = array();
	  $autoCreateAUserArray['email'] = $email;
	  $autoCreateAUserArray['type'] = '1';
	  $autoCreateAUserArray['password'] = $password;
      $createAMeetingArray["access_token"] = $this->getToken();
	  return $this->sendRequest('user/autocreate', $autoCreateAUserArray);
	}
public function createAMeeting($host_id, $topic, $start_time, $duration){
  $createAMeetingArray = array();
  $createAMeetingArray['host_id'] =$host_id;//'iK78ivWsQOiFS9q_CO9EnQ';
  $createAMeetingArray['topic'] = $topic;
  $createAMeetingArray['type'] = "2";
  $timestamp = strtotime($start_time);
  $gmtDate = gmdate("yyyy-MM-dd’T'HH:mm:ss'Z", $timestamp);
  $createAMeetingArray['start_time'] = $gmtDate;
  $createAMeetingArray["duration"] = $duration;
  
    $createAMeetingArray["access_token"] = $this->getToken();
  return $this->sendRequest('meeting/create', $createAMeetingArray);

}
public function getUserInfoByEmail($email){
  $getUserInfoByEmailArray = array();
  $getUserInfoByEmailArray['email'] = $email;
  $getUserInfoByEmailArray['login_type'] = '100';
  $createAMeetingArray["access_token"] = $this->getToken();
  return $this->sendRequest('user/getbyemail',$getUserInfoByEmailArray);
}
    public function getToken()
    {
    $payload = array(
        "iss" => $this->api_key,
        "exp" => (time()*1000 + 100000)
    );
    $key = "123";
    $jsonPayload = JWT::encode($payload,$key);
    $token = JWT::sign($jsonPayload,$key);
    }
}
?>