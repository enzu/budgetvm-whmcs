<?php
date_default_timezone_set('America/New_York');
if(!function_exists("curl_init")){
	die("You need curl installed for this library to function.");
}

class BudgetVM_Api {

	function __construct($key){
		$this->key = $key;
		$this->host = "https://portal.budgetvm.com/api";
	}
  
  public function ipmiLaunch($data){
  	header('Content-Type: application/x-java-jnlp-file', true);
  	header('Content-Disposition: attachment; filename="launch.jnlp"', true);
  	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT", true);
  	header('Accept-Ranges: bytes', true);
  	header("Cache-control: private", true);
  	header('Pragma: private', true);
  	echo $data;
  }
  
	public function call($version, $controller, $function, $method, $var){
		if(!isset($method)){
			return $this->__error("Missing Method");
		}elseif(!isset($version)){
			return $this->__error("Missing Version");
		}elseif(!isset($controller)){
			return $this->__error("Missing Controller");
		}elseif(!isset($function)){
			return $this->__error("Missing Function");
		}else{
      $api = new stdClass();
      $headers['0'] = 'X-API-KEY: ' . $this->key;
      $post = false;
      $put = false;
      $delete = false;
      if( strtolower($method) == 'post' ){
        $post = true;
      }else if( strtolower($method) == 'put' ){
        $post = true;
        $put = true;
        $headers['1'] = 'X-HTTP-Method-Override: PUT';
      }else if( strtolower($method) == 'delete' ){
        $post = true;
        $delete = true;
        $headers['1'] = 'X-HTTP-Method-Override: DELETE';
      }else{
        $api->query = "?";
        if(isset($var->post)){
          foreach ($var->post as $k => $v){
            $api->query .= $k . "=" . $v . "&";
          }
          $api->query = rtrim($api->query, "&");
        }
        $api->query = rtrim($api->query, "?");
      }

			$this->host = $this->host . "/" . $version . "/" . $controller . "/" . $function;
			if($post == true){
				$api->call = $this->host;
				foreach($var->post as $key => $value){
					$postdata[$key] = $value;
				}
				$api->query = http_build_query($postdata);
			}else{
				$api->call = $this->host . $api->query;
			}

      //die($api->call);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $api->call);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			if($post == true){				
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT, 15);
			}else{
				curl_setopt($ch, CURLOPT_POST, 0);
				curl_setopt($ch, CURLOPT_TIMEOUT, 15);
			}
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			if($put == true){
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
			}
			if($delete == true){
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
			}
			if($post == true){
				curl_setopt($ch, CURLOPT_POSTFIELDS, $api->query);
			}
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_VERBOSE, true);

			if(curl_error($ch)){
				return $this->__error("Connection Error: ".curl_errno($ch).' - '.curl_error($ch));
			}else{
				$data = json_decode(curl_exec($ch));
				curl_close($ch);
				return $this->__success($data->result);
				//return $headers['0'];
			}
		}
	}
	
	private function __success($var, $count = ""){
  	$data = new stdClass();
    $data->success = true;
		if(!empty($count) && is_numeric($count)){
      $data->results = $count;
		}else{
      $data->results = count((array)$var);
		}
		$data->result = $var;
  	return $data;	
  }

  private function __error($msg = "Unknown Error"){
		$data = new stdClass(); 
    $data->success = false;
		$data->results = "0";
		$data->result = $msg;
		return $data;	
	}
}

?>
