<?php

namespace Irfan\FCM;

use \Jyggen\Curl\Curl;
use \Jyggen\Curl\Request as Request;

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
        	if(is_array($target)){
			if (count($target) == 1) {
				$fields['condition'] = "'".$target[0]."' in topics";
			}
			if (count($target) >= 2) {
				$topics = '';
				foreach ($target as $k => $v) {
					if ($topics == '') {
						$topics = "'".$v."' in topics";
					}else {
						$topics .= " || '".$v."' in topics";
					}
				}
				$fields['condition'] = $topics;
			}
		}else{
				$fields['condition'] = "'".$target."' in topics";
		}

	    	$request = new Request($this->appURL);
		$request->setOption(CURLOPT_POST, true);
  		$request->setOption(CURLOPT_HTTPHEADER,$headers);
  		$request->setOption(CURLOPT_SSL_VERIFYHOST, 0);
  		$request->setOption(CURLOPT_SSL_VERIFYPEER, FALSE);
  		$request->setOption(CURLOPT_POSTFIELDS, json_encode($fields));
  		$request->execute();
  		$this->response = $request->getResponse();
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
  
?>
