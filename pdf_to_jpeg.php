<?
include 'function_vkapi.php';
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
// $ids  = file_get_contents(__DIR__.'/ids1.txt');
// $ids2 = file_get_contents(__DIR__.'/ids2.txt');
// $ids3 = file_get_contents(__DIR__.'/ids3.txt');
$mod_arr = json_decode(file_get_contents(__DIR__.'/current_source.txt'));	//получение информации о последнем сохранении на сервере
// set_status('sd');


 // "Напиши боту 'Начать' или номер корпуса (цифрой)"
// echo send_mass("В ходе обновления вы были отписаны от расписания, подпишитесь обратно",$ids,'', 1);
// echo send_mass("В ходе обновления вы были отписаны от расписания, подпишитесь обратно",$ids2,'', 1);
// echo send_mass("В ходе обновления вы были отписаны от расписания, подпишитесь обратно",$ids3,'', 1);
// send_mass("Расписание 2 корпуса обновилось.",$ids,$att);


// $user_id = '137038675';
// if (strpos($user_id, ',')===false) {
//     echo "Строка не найдена";
// }else{
// 	echo "string";
// }

// 	$request_params = array( 
// 		'message' => '$text', 
// 		'peer_id' => $user_id, 
// 		'attachment'  => $attach,
// 		'keyboard' =>$keyboard,
// 		'access_token' => $token, 
// 		'v' => '5.102'); 
		
// 		$get_params = http_build_query($request_params); 
// 		echo('https://api.vk.com/method/messages.send?'. $get_params); 
// $text = "jdkfghsdkfghldf";
// $method = 'messages.send';
// $url = sprintf( 'https://api.vk.com/method/%s', $method);
// $ch = curl_init();
// curl_setopt_array( $ch, array(
//     CURLOPT_POST    => TRUE,            // это именно POST запрос!
//     CURLOPT_RETURNTRANSFER  => TRUE,    // вернуть ответ ВК в переменную
//     CURLOPT_SSL_VERIFYPEER  => FALSE,   // не проверять https сертификаты
//     CURLOPT_SSL_VERIFYHOST  => FALSE,
//     CURLOPT_POSTFIELDS      => array(   // здесь параметры запроса:
//         'user_ids'   => $user_id,
//         'message' => $text, 
//         'access_token' => $token,
//         'v' => '5.9' 
//     ),
//     CURLOPT_URL             => $url,    // веб адрес запроса
// ));
// echo curl_exec($ch); // запрос выполняется и всё возвращает в переменную
// curl_close( $ch);


//send_mass('Сообщение отправлено с целью обновить кнопки у всех пользователей бота, чтобы программа отрабатывала одинаково. Раз уж все равно приходиться делать рассылку, то опишу некоторую информацию. Во-первых, бот был полностью переписан и на данный момент все известные баги были исправлены. Во-вторых, теперь доступна функция подписки на обновления расписания(бот будет отправлять новое расписание как только заметит его. Это происходит не больше 3 раз в день, обычно лишь один). Чтобы получать информацию по поводу бота можно подписаться на рассылку: https://vk.com/vkc_bot?w=app5748831_-177112813. Подписчики этой рассылки смогут первыми протестировать новые функции бота. Если возникнут вопросы или баги, пишите разработчику в ЛС:vk.com/id137038675 или в комментариях под постом в сообществе.',$user_id,"",1);
//получение списка диалогов
 	// 	$request_params = array( 
		// 'count' => 200, 
		// 'offset'=>200,
		// 'access_token' => $token, 
		// 'v' => '5.9'); 
		
		// $get_params = http_build_query($request_params); 
		// $dialogs = json_decode(file_get_contents('https://api.vk.com/method/messages.getConversations?'. $get_params)); 
		// //echo $dialogs->response->items[1]->conversation->peer->id;
		// $ids_dia = array();
		// foreach ($dialogs->response->items as $key) {
		// 	if ($key->conversation->can_write->allowed) {
		// 		//array_push($ids_dia,$key->conversation->peer->id);
		// 		echo $key->conversation->peer->id.',<br>';
		// 	}
			
		// }
		
		//var_dump($ids_dia);





//когда изменялось расписане 2 корпуса
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://rasp.kolledgsvyazi.ru/npo.pdf');
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'HEAD');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$content = curl_exec ($ch);
curl_close ($ch);
$last_npo = explode ("\r\n",$content)[3];
//когда изменялось расписане 1 корпуса
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://rasp.kolledgsvyazi.ru/spo.pdf');
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'HEAD');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$content = curl_exec ($ch);
curl_close ($ch);
$last_spo = explode ("\r\n",$content)[3];

//обновлялось ли расписание после последней загрузки
if ($last_npo != $mod_arr->npo || $last_spo != $mod_arr->spo) {
	
	$link = new mysqli('localhost', "cm56270_like", '10119alina', 'cm56270_like');
	if ( !$link ) die("ошибка баз данных");
	$result = mysqli_query($link, "SELECT * FROM `users` WHERE `subscribe_status` = 1");

	for ($ids = []; $row = mysqli_fetch_assoc($result); $ids[] = $row['id']);
	$ids_chunk = array_chunk($ids, 5);

}
//2 корпус
if ($last_npo != $mod_arr->npo) {
	$fp_pdf = fopen("http://rasp.kolledgsvyazi.ru/npo.pdf", 'rb');

	$_img = new imagick(); // [0] can be used to set page number
	$_img->setResolution(300,300);
	$_img->readImageFile($fp_pdf);
	$page = array();
	$i=0;
	foreach($_img as $img) {
		$i++;
		$img->setImageFormat( "jpg" );
		$img->setImageCompression(imagick::COMPRESSION_JPEG); 
		$img->setImageCompressionQuality(90); 
		$img->setImageUnits(imagick::RESOLUTION_PIXELSPERINCH);
		$img->adaptiveResizeImage(2560, 1810);
		$bg = new imagick('https://sun9-69.userapi.com/ZtS_COtB7iNP3hMdR_r3CprtYDvsuYu2Pb5CbA/Z_V8DpNTLXo.jpg');
		$bg->compositeImage($img, imagick::COMPOSITE_MULTIPLY, 0, 0);
		$bg->writeImage(__DIR__.'/npo'.$i.'.jpg');

		//echo "string";
		array_push($page,create_photo("npo".$i.".jpg"));
		
	}
	$mod_arr->npo_str = implode(',', $page);
	$att = array($mod_arr->npo_str);
	$_img->destroy();
	
	foreach ($ids_chunk as $ids) {
		$ids = implode(',', $ids );
		echo send_mass("Расписание 2 корпуса обновилось.<br> В высоком качестве для мобильной версии: vk.cc/az9qeX",$ids,$att);
      	sleep(0.02);
	}
	
    $mod_arr->npo = $last_npo;
}

//1 корпус
if ($last_spo != $mod_arr->spo) {
	$fp_pdf = fopen("http://rasp.kolledgsvyazi.ru/spo.pdf", 'rb');

	$_img = new imagick(); // [0] can be used to set page number
	$_img->setResolution(300,300);
	$_img->readImageFile($fp_pdf);
	$page = array();
	$i=0;
	foreach($_img as $img) {
		$i++;
	   	$img->setImageFormat( "jpg" );
		$img->setImageCompression(imagick::COMPRESSION_JPEG); 
		$img->setImageCompressionQuality(90); 
		$img->setImageUnits(imagick::RESOLUTION_PIXELSPERINCH);
		$img->adaptiveResizeImage(2560, 1810);
		$bg = new imagick('https://sun9-69.userapi.com/ZtS_COtB7iNP3hMdR_r3CprtYDvsuYu2Pb5CbA/Z_V8DpNTLXo.jpg');
		$bg->compositeImage($img, imagick::COMPOSITE_MULTIPLY, 0, 0);
		$bg->writeImage(__DIR__.'/spo'.$i.'.jpg');
		//echo "string";
		array_push($page,create_photo("spo".$i.".jpg"));
	}
	$img->destroy();

	$mod_arr->spo_str = implode(',', $page);
	$att = array($mod_arr->spo_str);
	foreach ($ids_chunk as $ids) {
		$ids = implode(',', $ids );
		echo send_mass("Расписание 1 корпуса обновилось.<br> В высоком качестве для мобильной версии: vk.cc/az9qAf",$ids,$att);
      	sleep(0.02);
	}
	$mod_arr->spo = $last_spo;
}


$mdf_str = json_encode($mod_arr);
file_put_contents(__DIR__.'/current_source.txt',$mdf_str);