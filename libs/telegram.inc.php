<?php

// Самодельная библиотека TGM

define('BOT_TOKEN', '1285006817:AAGQf7tp1tMP7Zo4zkjupB1Yp6XA-ob0Tmo');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

function apiRequestWebhook($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  header("Content-Type: application/json");
  echo json_encode($parameters);
  return true;
}

function exec_curl_request($handle) {
  $response = curl_exec($handle);

  if ($response === false) {
    $errno = curl_errno($handle);
    $error = curl_error($handle);
    error_log("Curl returned error $errno: $error\n");
    curl_close($handle);
    return false;
  }

  $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));



  curl_close($handle);

  if ($http_code >= 500) {
    // do not wat to DDOS server if something goes wrong
    sleep(10);
    return false;
  } else if ($http_code != 200) {
    $response = json_decode($response, true);
    error_log("Request has failed with error {$response['error_code']}: {$response['description']}\n");
    if ($http_code == 401) {
      throw new Exception('Invalid access token provided');
    }
    return false;
  } else {
    $response = json_decode($response, true);
    if (isset($response['description'])) {
      error_log("Request was successful: {$response['description']}\n");
    }
    $response = $response['result'];
  }

  return $response;
}

function apiRequest($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  foreach ($parameters as $key => &$val) {
    // encoding to JSON array parameters, for example reply_markup
    if (!is_numeric($val) && !is_string($val)) {
      $val = json_encode($val);
    }
  }
  $url = API_URL.$method.'?'.http_build_query($parameters);

  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);

  return exec_curl_request($handle);
}

function apiRequestJson($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  $handle = curl_init(API_URL);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  curl_setopt($handle, CURLOPT_POST, true);
  curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
  curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

  return exec_curl_request($handle);
}



function processMessage($message) {
  // process incoming message
  $message_id = $message['message_id'];
  $chat_id = $message['chat']['id'];
  if (isset($message['text'])) {
    // incoming text message
    $text = $message['text'];

	if (isCommand($update, "smena")) {
		apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'Ура! Начинаем работать'));
	}

    if (strpos($text, "/start") === 0) {
      apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => 'Hello', 'reply_markup' => array(
        'keyboard' => array(array('Hello', 'Hi')),
        'one_time_keyboard' => true,
        'resize_keyboard' => true)));
    } else if ($text === "Hello" || $text === "Hi") {
      apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'Nice to meet you'));
    } else if (strpos($text, "/stop") === 0) {
      // stop now
    } else {
      apiRequestWebhook("sendMessage", array('chat_id' => $chat_id, "reply_to_message_id" => $message_id, "text" => 'Cool'));
    }
  } else {
    apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'I understand only text messages'));
  }
}


function tgm_GetPhone($update) {
    return $update['message']['contact']['phone_number']??false;
}

function tgm_GetChatID($update) {
    $chatID = $update['message']['chat']['id']??false;
    if (!$chatID) {
      $chatID = $update['callback_query']['message']['chat']['id']??false;
    }
    return $chatID;
}


function tgm_inBtn ($text, $callbackData='') {
  // Создаёт Inline"кнопку" c подпись $text и возвращаемым значением $callbackData
  return array('text'=>$text, 'callback_data'=>$callbackData);
}


function tgm_callBackCommand($update, $callBackCommand) {
  // Отлавливаем callback команду с названием $callBackCommand
  $cb = $update['callback_query']['data']??'';
  if (strpos($cb, $callBackCommand)===0) {
    return substr($cb, strlen($callBackCommand));  
  } else return false;
}

function tgm_SendMessage($chat_id, $text, $inline_keyboard=NULL, $keyboard=NULL) {
/* Отправляет сообщение $msg в чат $chatID вместе с $inlineButtons или $replyBtns
  $inlineButtons или $replyBtns - массив строк, каждая из которых - массив кнопок, каждая из которых - массив из 1-2 элементов */ 
	$parameters['chat_id'] = $chat_id;
  $parameters['parse_mode'] = 'HTML';
	$parameters['text'] = $text;
  if ($inline_keyboard) {
    $reply_markup = new stdClass();
    $reply_markup->inline_keyboard = $inline_keyboard;
    $parameters['reply_markup'] = json_encode($reply_markup);    
  } elseif ($keyboard) {
    $reply_markup = new stdClass(); 
    $reply_markup->keyboard=$keyboard;
    $reply_markup->one_time_keyboard = true;
    $reply_markup->resize_keyboard = true;
    $parameters['reply_markup'] = json_encode($reply_markup);
  }
  $url = API_URL.'sendMessage?'.http_build_query($parameters);
  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  $reply = exec_curl_request($handle);
  return $reply;
}

function tgm_answerCallbackQuery($callback_query_id, $text, $showAlert = false) {
// Ответ на callback query
  $parameters['callback_query_id'] = $callback_query_id;
  $parameters['text'] = $text;
  $parameters['show_alert'] = $showAlert;
  $url = API_URL.'answerCallbackQuery?'.http_build_query($parameters);
  $handle = curl_init($url);  
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  $reply = exec_curl_request($handle);
}

function tgm_editMessageText($chatID, $message_id, $text, $inlineBtns=NULL, $replyBtns=NULL) {
// Работает аналогично tgm_sendMessage, но дополнительно принимает $message_id и изменяет его.
	$parameters['chat_id'] = $chatID;
  $parameters['message_id'] = $message_id;
  $parameters['parse_mode'] = 'HTML';
	$parameters['text'] = $text;
  if ($inlineBtns) {
    $reply_markup = new stdClass();
    $reply_markup->inline_keyboard = $inlineBtns;
    $parameters['reply_markup'] = json_encode($reply_markup);    
  } elseif ($replyBtns) {
    $reply_markup = new stdClass();
    $reply_markup->keyboard=$replyBtns;
    $reply_markup->one_time_keyboard = true;
    $reply_markup->resize_keyboard = true;    
    $parameters['reply_markup'] = json_encode($reply_markup);
  }
  $url = API_URL.'editMessageText?'.http_build_query($parameters);
  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  $reply = exec_curl_request($handle);
  return $reply;
}