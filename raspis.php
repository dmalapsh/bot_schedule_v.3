<?php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 'on');
include 'function_vkapi.php';





//echo $file_age = (int)((time() - filemtime('current_source.txt'))/60);	
//   file_put_contents('current_source.txt'," hi,and this is ok");

	// 	if (!isset($_REQUEST)) { 
	// return; 
	// } 

// 	Получаем и декодируем уведомление 
	$data = json_decode(file_get_contents('php://input')); 
	//send_mass('олр',137038675);	

	switch ($data->type) { 
	case 'confirmation': 
		echo $confirmation_token; 
	break; 
	//Если это уведомление о новом сообщении... 
	case 'message_new': processing_mess();
	break; 
	//если запрет на отправку сообщений
	case 'message_deny':
		$user_id = $data->object->user_id; 
		subscribe($user_id, 0);
	}
	
	
	
	
	
	
	
	
	
	function processing_mess(){
		global $data,$min_rate;
		$user_id = $data->object->peer_id; 
		switch($data->object->text){
		    case "1":case "🏢 1 корпус": case "🏢 Корпус 1": case '1 корпус':case 'корпус 1':case '/1':case '[club177112813|@vkc_bot] 🏢 1 корпус':case '[club177112813|ВКСиИТ - Расписание | БОТ] 🏢 1 корпус':
		        $mod_arr = json_decode(file_get_contents('current_source.txt'));
		        $att = array($mod_arr->spo_str);
		        send_mass("В высоком качестве для мобильной версии: vk.cc/az9qAf",$user_id,$att);
		    break;
		    case "2":case "🏢 2 корпус": case"🏢 Корпус 2": case '2 корпус':case 'корпус 2':case '/2':case '[club177112813|@vkc_bot] 🏢 2 корпус':case '[club177112813|ВКСиИТ - Расписание | БОТ] 🏢 2 корпус':
		    	$mod_arr = json_decode(file_get_contents('current_source.txt'));
		        $att = array($mod_arr->npo_str);
               send_mass("В высоком качестве для мобильной версии: vk.cc/az9qeX",$user_id,$att);		        
            break;
            case "/Какие пары":case "/какие пары":case "/расписание":case "/Расписание":
            	send_mass("Какой корпус?",$user_id,'',3);
            break;
            case "Какие пары":case "какие пары": case 'Расписание':
                send_mass("Какой корпус?",$user_id,'',1);
                break;
			case "счет" :
				$out = "На вашем счете ".bd_serch($user_id);
				send_mass($out,$user_id);
			break;
			case "test" :
				subscribe($user_id);
				send_mass("удачно",$user_id);
			break;
			case "Пожертвовать":case "пожертвовать":
				send_mass("Чтобы бот быстро работал - нужен быстрый сервер для обработки сообщений и обновления расписания. Стоймость годовой аренды состовляет 1600руб <br> На данный момент бот работает на уже оплаченном сервере, однако его аренда заканчивается 20 марта. Если 50 человек задонатит по 32 рубля, то мы уже наберем нужную сумму<br> Донат можно отправить:<br> через сообщения, нажав на скрепку и выбрав пункт деньги(Увы ВК не разрешает таким методом переводить ментше 50руб, а VKPay мало кто пользуеться)<br> Через приложение сообщества: vk.com/vkc_bot?w=app5727453_-177112813",$user_id);
				break;
			case "Начать": case "Справка": case "справка": case"?":
				send_mass('- Чтобы получить расписание, отправьте цифровой номер вашего корпуса. Или нажмите на нужную кнопку.<br> - Нажав на кнопку "Подписаться на обн. распис", вы будете получать фото нового расписания как только оно обновиться на сайте колледжа<br> - Получать информацию об оновлении бота и тестировать новые функции можно подписавшись на рассылку vk.cc/9UK1Nq<br> Статья FAQ - вопрос-ответ(инструкция) vk.com/@vkc_bot-faq<br> Любые вопросы сюда: vk.com/id137038675',$user_id,"",1);
			break;
			case 'Отписаться':
				subscribe($user_id, 0);
				send_mass('Вы отписаны от рассылки', $user_id,'',1);
				break;

			case "Подписаться на обн. распис.":
				// send_mass('Подписка на обновление расписания временно не доступна по причинам указанным на стене сообщества. Если есть желание присоединится к разработке бота пиши в лс разрабу https://vk.com/id137038675',$user_id, "", 2);
				subscribe($user_id);
				send_mass('Вы подписаны на обновление расписания',$user_id,"", 2);
				// $ids =  file_get_contents("ids1.txt");
				// $ids2 = file_get_contents("ids2.txt");
				// $ids3 = file_get_contents("ids3.txt");
				// if ((strpos($ids,','.$user_id) === false) && (strpos($ids2,','.$user_id) === false)&& (strpos($ids3,','.$user_id) === false)) {
				// 	file_put_contents('./ids3.txt', PHP_EOL . ','.$user_id, FILE_APPEND);
				// 	send_mass('Теперь вы подписаны на обновление расписания',$user_id,"",2);
				// }else{
				// 	send_mass('Вы уже подписаны на обновление расписания',$user_id, "", 2);
				// }
			

				break;
			case "/проверка":
				
				send_mass("Подписано ".count_users(),$user_id,"");
				
			break;
			case '/убрать клаву':
				send_mass("Клавиатура убрана",$user_id,"",-1);
				break;
			case "пере":
				$attach = $data->object->attachments;
				$out_att = array();

				foreach ($attach as $value) {
				 	$type = $value->type;
				 	$id = $value->$type->id;
				 	$owner_id = $value->$type->owner_id;
				 	$str_push = $type.$owner_id."_".$id;
				 	array_push($out_att,$str_push);
					}
				 
				send_mass("",$user_id,$out_att);
			break;
			default:
				$ret = mess_dct($data->object->text);
				if ($ret) {
					send_mass($ret,$user_id);
					break;
				}
				

				if($data->object->text{0} == "*"){				//если первй символ *
					$rest = substr($data->object->text, 1);
					$ball = bd_serch($user_id);
					if ($rest<=$ball){
						if ($rest>=$min_rate){
						switch (rand(1, 4)){
							case 1:
								$ball = $ball + $rest;
								bd_edit($user_id,$ball);
								send_mass("увеличилось на {$rest}, теперь у вас {$ball}",$user_id);
							break;
							case 2:
								$rest = $rest/2;
								$ball = $ball + $rest;
								bd_edit($user_id,$ball);
								send_mass("увеличилось на {$rest} теперь у вас {$ball}",$user_id);
							break;
							case 3:
								$rest = $rest/2;
								$ball = $ball - $rest;
								bd_edit($user_id,$ball);
								send_mass("уменьшилось на {$rest} теперь у вас {$ball}",$user_id);
							break;
							case 4: 
								$ball = $ball - $rest;
								bd_edit($user_id,$ball);
								send_mass("уменьшилось на {$rest} теперь у вас {$ball}",$user_id);
							break;
								};
							}else{
								send_mass("Минимальная ставка {$min_rate}",$user_id);
							};
						
						}else{
						send_mass("У вас лишь {$ball}",$user_id);}
				}
				else{
					if (strpos($data->object->text,"[club177112813|") !== false) {
						$mod_arr = json_decode(file_get_contents('current_source.txt'));
						$att = array($mod_arr->npo_str.','.$mod_arr->spo_str);
               			send_mass("Вот расписание первого и второго корпуса",$user_id,$att);						
						break;
					}
				 $attach = $data->object->attachments;
				 $text = $data->object->text;
				 $out_att = array();

				foreach ($attach as $value) {
				 	$type = $value->type;
				 	$id = $value->$type->id;
				 	$key = $value->$type->access_key;
				 	$owner_id = $value->$type->owner_id;
				 	$str_push = $type.$owner_id."_".$id."_".$key;
				 	array_push($out_att,$str_push);
					}
				 
				send_mass($text,$user_id,$out_att);
					
				};
		 }
		 echo('ok');
	}
 	

	//send_mass("",137038675,array("photo445654414_456239253"));

function count_users(){
	$link = new mysqli('localhost', "cm56270_like", '10119alina', 'cm56270_like');
if ( !$link ) die("ошибка баз данных");
$result = mysqli_query($link, "SELECT COUNT(*) FROM `users` WHERE `subscribe_status` = 1");

return mysqli_fetch_assoc($result)["COUNT(*)"];
}

function mess_dct($text)
{
	$text = mb_strtolower($text);
	// return $text;
	switch ($text) {
		case 'спасибо':case 'спс':
			return 'Пожалуйста';
			break;
		case 'супер':
			return 'Я рад';
			break;
		// case '':
		// 	return '';
		// 	break;
		// case '':
		// 	return '';
		// 	break;
		default:
			return false;
			break;
	}
}

function subscribe($user_id, $status = 1){
	$link = new mysqli('localhost', "cm56270_like", '10119alina', 'cm56270_like');
	if ( !$link ) die("ошибка баз данных");
	$bd_user_ids = mysqli_query($link, "SELECT * FROM `users` WHERE `id`IN ($user_id)");
	if (!mysqli_fetch_row($bd_user_ids)) {
		$link ->query("INSERT INTO users (id, point) VALUE ('".$user_id."', '".$default_scrope."')");
	}
	$result = $link ->query("UPDATE `users` SET subscribe_status = $status WHERE `id`= $user_id");
}

function bd_serch($user_id){	
	$link = new mysqli('localhost', "cm56270_like", '10119alina', 'cm56270_like');
	if ( !$link ) die("ошибка баз данных");
	$link ->query("SET NAMES 'utf8' ");
	$query = 'SELECT * FROM `users` WHERE `id`IN ('.$user_id.')';//;SELECT * FROM users WHERE age IN (21,26,33)
	$bd_user_ids = mysqli_query($link,$query);
		
	if($varible = mysqli_fetch_row($bd_user_ids)){		//условие существования id в базе данных
		return	$varible[1];
	}else{
		global $default_scrope;
		$link ->query("INSERT INTO users (id, point) VALUE ('".$user_id."', '".$default_scrope."')");
		return $default_scrope;
	};
	mysqli_close($link);

}
function bd_edit($user_id,$value){
	global $bd_pass;
	$link = new mysqli('localhost', "cm56270_like", $bd_pass, 'cm56270_like');
	$link ->query("UPDATE `users` SET point ='{$value}' WHERE `id`= '".$user_id."'");
}
		