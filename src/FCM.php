<?php

namespace Juniorsev3n\FCM;


class FirebaseManager
{
  	private $key = 'yourfcmkey';
  	private $appURL = 'https://fcm.googleapis.com/fcm/send';
	private $device_target;
	private $data;
	private $response;
	private $notification;

	public function sendPush()
	{
    	$headers = array('Authorization:key=' . $this->key,
			 			 "Content-Type: application/json");
    	$fields = array();
		$target = $this->device_target;
    	$fields['data'] = $this->data;
    	if(is_array($target))
    	{
			if(count($target) == 1)
			{
				$fields['condition'] = "'".$target[0]."' in topics";
			}
		
			if (count($target) >= 2)
			{
				$topics = '';
				
				foreach ($target as $k => $v)
				{
					if ($topics == '')
					{
						$topics = "'".$v."' in topics";
					}else {
						$topics .= " || '".$v."' in topics";
					}
				}
			
				$fields['condition'] = $topics;
			}
		}else {
			$fields['condition'] = "'".$target."' in topics";
		}

		
		$init = curl_init();

		$options = array(
		    CURLOPT_URL => $this->appURL,
		    CURLOPT_POST => true, // GET POST PUT PATCH DELETE HEAD OPTIONS 
		    CURLOPT_POSTFIELDS => json_encode($fields),
		    CURLOPT_HTTPHEADER => $headers,
		    CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => FALSE
		  ); 
		
		curl_setopt_array($init,($options)); 

  		$this->response = json_decode(curl_exec($init),true);
	}
	
	public function setTarget($push_target)
	{
		$this->device_target = $push_target;
	}

	public function setData($push_data)
	{
		$this->data = $push_data;
	}

	public function setTarget($target)
	{
		$this->device_target = $target;
	}

	public function setNotification($notif)
	{
		$this->notification = $notif;
	}

	public function getResponse()
	{
		return $this->response;
	}

}
