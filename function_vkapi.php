<?
include 'settings.php';
function create_photo($src){
    global $token;
	$request_params = array( 
    "peer_id" => 137038675,
	'access_token' => $token, 
	'v' => '5.21'); 
	$get_params = http_build_query($request_params); 
	$response = json_decode(file_get_contents('https://api.vk.com/method/photos.getMessagesUploadServer?'.$get_params))->response; 
    $url = $response->upload_url;
	$curl_file = curl_file_create(__DIR__ . '/'.$src, 'mimetype' , 'image.jpeg');
	 
	$ch = curl_init($url);  
	curl_setopt($ch, CURLOPT_POST, 1);  
	curl_setopt($ch, CURLOPT_POSTFIELDS, array('photo' => $curl_file));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HEADER, false);
	$res = json_decode(curl_exec($ch));
	curl_close($ch);	
	$request_params = array( 
    "photo" => $res->photo,
    "server" =>$res->server,
    "hash" =>$res->hash,
	'access_token' => $token, 
	'v' => '5.21'); 
	
	$get_params = http_build_query($request_params); 
	$photo = json_decode(file_get_contents('https://api.vk.com/method/photos.saveMessagesPhoto?'.$get_params))->response[0];
	return "photo".$photo->owner_id."_".$photo->id."_".$photo->access_key;
}


function set_status($text){

	global $token;
	$request_params = array( 
		'type' => 'text',
		'access_token' => $token, 
		'code' => 'return {
				title": "","
			    "text": "Подписано уже более 300 человек. Присоединяйся!"
			};', 
	'v' => '5.40'); 
	
	$get_params = http_build_query($request_params); 
	echo file_get_contents('https://api.vk.com/method/appWidgets.update?'. $get_params); 
}

function send_mass($text, $user_id, $attach = "",$btn = false){
	switch ($btn) {
		case -1:
			$keyboard ='{"buttons":[]}';
			break;
		case 1:
			$keyboard = '{"buttons":[
	        [{"action":{"type":"text","label":"🏢 1 корпус"},"color":"negative"},{"action":{"type":"text","label":"🏢 2 корпус"},"color":"negative"}], 
	        [{"action":{"type":"text","label":"Подписаться на обн. распис."},"color":"positive"}]
	        ],"one_time":false}';
			break;
		case 2:
			$keyboard = '{"buttons":[
	        [{"action":{"type":"text","label":"🏢 1 корпус"},"color":"negative"},{"action":{"type":"text","label":"🏢 2 корпус"},"color":"negative"}],
	        [{"action":{"type":"text","label":"Отписаться"},"color":"positive"}] 
	        ],"one_time":false}';
			break;
		case 3:
			$keyboard = '{"buttons":[
	        [{"action":{"type":"text","label":"🏢 1 корпус"},"color":"negative"},{"action":{"type":"text","label":"🏢 2 корпус"},"color":"negative"}]
	        ],"one_time":false}';
				break;
	}

		if	($attach == "") {
			unset($attach);
		}else{
			$attach = implode(',', $attach);
		}
		if ($text == ""){
			unset($text);}
		
		
	global $token;
 		$request_params = array( 
		'message' => $text, 
		'user_ids' => $user_id, 
		'attachment'  => $attach,
		'keyboard' =>$keyboard,
		'access_token' => $token, 
		'v' => '5.9'); 
		
		$get_params = http_build_query($request_params); 
		file_get_contents('https://api.vk.com/method/messages.send?'. $get_params); 


		
		if (strpos($user_id, ',')===false) {
		    $type_id = 'peer_id';
		}else{
			$type_id = 'user_ids';
		}

		$method = 'messages.send';
		$url = sprintf( 'https://api.vk.com/method/%s', $method);
		$ch = curl_init();
		curl_setopt_array( $ch, array(
		    CURLOPT_POST    => TRUE,            // это именно POST запрос!
		    CURLOPT_RETURNTRANSFER  => TRUE,    // вернуть ответ ВК в переменную
		    CURLOPT_SSL_VERIFYPEER  => FALSE,   // не проверять https сертификаты
		    CURLOPT_SSL_VERIFYHOST  => FALSE,
		    CURLOPT_POSTFIELDS      => array(   // здесь параметры запроса:
		        $type_id   => $user_id,
		        'message' => $text, 
		        'attachment'  => $attach,
		        'keyboard' =>$keyboard,
		        'access_token' => $token,
		        'v' => '5.38' 
		    ),
		    CURLOPT_URL             => $url,    // веб адрес запроса
		));
		$rest = curl_exec($ch); // запрос выполняется и всё возвращает в переменную
		curl_close( $ch);
		return $rest;
 	};
