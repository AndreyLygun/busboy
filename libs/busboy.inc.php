<?php
# encoding: utf-8 (# coding: utf-8)

include_once 'telegram.inc.php';
global $modx;


// Здесь храним тексты комманд для кастомной клавиатуры.
$cmd['ru'] = [
        'start'=>'Начинаем смену',
        'stop'=>'Заканчиваем смену',
        'staff'=>'Сотрудники',
        'places'=>'Выбрать столы',
        'checkPlace'=>'Посмотреть стол',
    ];
$cmd = $cmd['ru'];


function translit($str) {
    $rus = array(' ', 'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
    $lat = array('_', 'A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
    $str = str_replace($rus, $lat, $str);
    return preg_replace('/[^_a-z\d]/ui', '-', $str);
}


function bb_clearCache($context = '') {
    global $modx;
    $modx->cacheManager->refresh();
    return;
    if ($context=='') $modx->cacheManager->refresh();
    else $modx->cacheManager->refresh(array(
            'resource' => ['contexts' => array($context)]
            ));
}

function bb_customReplyBtns($cmd) {
    $btns = array(
//      [ ['text'=>$cmd['checkPlace']] ],
      array(['text'=>$cmd['places']], ['text'=>$cmd['staff']]),
      array(['text'=>$cmd['start']], ['text'=>$cmd['stop']])
    );
    return $btns;
}

// Сохраняем сообщение  в лог-файл
function toLog($msg) {
    $filename = 'bb_'.date("Ymd").'.log';
    $fd = fopen($_SERVER["DOCUMENT_ROOT"]."/bb/".$filename, "a");
    $date = date("d.m.y H:i:s");
    fwrite($fd, $date.": ".$msg."\n");
    fclose($fd);
}

// Формируем JSON строку с кодом результата и сообщением о результате. 
function rslt($status, $msg, $id=0) {
    return "{\"status\": \"$status\", \"msg\" : \"$msg\", \"id\": \"$id\"}";    
}

function parseTemplate(string $template, array $parameters) {
    foreach($parameters as $key=>$value) {
        $template = str_replace('[[+'.$key.']]', $value, $template);
    }
}

// Берёт из массива $_FILES запись с ключом $name и сохраняет загруженный файл в папку $folder на сервере. Возвращает полный путь к сохранённому файлу.
// Если произошла ошибка, возвращает FALSE
// Проверяет расширение файла, сравнивая с списком допустимых расширений в массиве $allowedExt
function saveUploadedFile($name, $folder, $allowedExt=[]) {
    if (!isset($_FILES[$name]) or $_FILES[$name]['error']) return FALSE;
    $file = $_FILES[$name];
    $fileinfo = pathinfo($file['name']);
    if ($allowedExt and !in_array($fileinfo['extension'], ['jpg', 'jpeg', 'png', 'gif'])) return FALSE;
    $filename = $folder . translit($fileinfo['filename']) . '_'.time() . '.' . $fileinfo['extension'];
    if (move_uploaded_file($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/' . $filename)) return $filename;        else echo "UPS!";
}


// Обновляем (или добавляем) информацию о пользователе Телеграмм: $chatID, $companyID.
// $companyID - место, где работает пользователь сейчас. Если пусто - пользователь неактивен.
function bb_UpdateUserInfo($phone, $tgmChatID, $companyID) {    
    global $modx;
    $q = "INSERT INTO bb_Users (emplPhone, tgmChatID, companyID) VALUES(?, ?, ?) ON DUPLICATE KEY UPDATE tgmChatID = ?, companyID = ?";
    $stmt = $modx->prepare($q);
    $stmt->execute(array($phone, $tgmChatID, $companyID, $tgmChatID, $companyID));
    return $stmt->fetchAll();
}

//Возвращаем список подключенных столов для текущего чата
function bb_Places($chatID) {
    global $modx;
    if ($chatID=="") {return '';}	
    $stmt = $modx->prepare("SELECT `bb_Tables`.`tableID`, tableName FROM `bb_QRs`, bb_Tables WHERE `bb_QRs`.`tableID` = `bb_Tables`.`tableID` AND `qrID`=?");
    $stmt->execute(array($qrID));
    $result = $stmt->fetchAll();
    if (isset($result['0'])) {
        return($result['0']);
    } else return('');
}


// Возвращает по QR-коду телефоны сотрудников, обслуживающих данное место.
function bb_GetPhonesByQR($qrCode) {
    global $modx;
    if (!$qrCode) return false;
    $sql = 'SELECT * FROM bb_QRs WHERE `id`*10000+`stamp` = ?';
    $q = $modx->prepare($sql);
    $q->execute([$qrCode]);
    $rslt = $q->fetchAll();
    if (count($rslt)==0) return false;
    $placeId = $rslt[0]['placeID'];     
    $sql =  'SELECT bb_Users.emplPhone FROM bb_QRs, bb_Places, bb_Service, bb_Users'.
            ' WHERE bb_QRs.placeID = bb_Places.id'.
            ' AND bb_Places.id = bb_Service.placeID'.
            ' AND bb_Service.emplPhone = bb_Users.emplPhone'.
            ' AND bb_Places.companyKey = bb_Users.companyID'.
            ' AND bb_QRs.id*10000+bb_QRs.stamp= ?';
    $stmt = $modx->prepare($sql);
    $stmt->execute(array($qrCode));
    $result = $stmt->fetchAll();
    return($result);
}



function bb_PlacesSelectBtns($phone) {
    $btnRow = [];
// Выводим кнопки для выбора мест, которые (не)обслуживает официант с указанным телефоном
    global $modx;
    $q= 'SELECT places.id, places.location, places.name, if(isNULL(service.emplPhone), 0, 1) as served FROM `bb_Places` as places' .
        ' LEFT JOIN (SELECT * FROM bb_Service WHERE bb_Service.emplPhone=?) AS service' . 
        ' ON places.id=service.placeID' .
        ' WHERE places.companyKey=?' .
        ' ORDER BY `places`.`name`  ASC';
    $companyKey = bb_getServedCompany($phone);
    $stmt = $modx->prepare($q);      
    $stmt->execute([$phone, $companyKey]);
    $places = $stmt->fetchAll();
    $btns=[];
    $settings = bb_getObject('bb_Companies', $companyKey);
    if ($settings['pickup'] or $settings['delivery']) {
        echo $q = 'SELECT outside FROM bb_Staff WHERE emplPhone = ? AND companyID = ?';
        echo $phone, $companyKey;
        $stmt = $modx->prepare($q);
        $stmt->execute([$phone, $companyKey]);
        $result = $stmt->fetchAll();
        if ($result[0]['outside']) {
            $btnRow[]=tgm_inBtn('#Доставка/Вынос', 'cbPlace_Outside_Remove');
        } else {
            $btnRow[]=tgm_inBtn('Доставка/Вынос', 'cbPlace_Outside_Add');
        }
        $btns[]=$btnRow;
    }
    $btnRow = [];
    for($i=0; $i<count($places); $i++) {
        list($pid, $location, $name, $served) = $places[$i];
        if ($served) {
            $btnTitle = "#$name";
            $cbData = "cbPlace_Remove_$pid";       //callBack, которая удаляет запись об обслуживании
        } else {
            $btnTitle = $name;
            $cbData = "cbPlace_Add_$pid";       //callBack, которая удаляет запись об обслуживании
        }
        $btnRow[]=tgm_inBtn($btnTitle, $cbData); 
        if (count($btnRow)>=5) {
            $btns[] = $btnRow;
            $btnRow = [];        
        }            
    }
    if (count($btnRow)>0) {
        for ($i=count($btnRow); $i<5; $i++) $btnRow[]=tgm_inBtn(' ', '-'); // Добиваем до 5 кнопок в строке
        $btns[]=$btnRow;
    }
    return ($btns);
}


function bb_GetTableByQR($qrID) {
    global $modx;
    if ($qrID=="") {return '';}
    $stmt = $modx->prepare("SELECT `bb_Tables`.`tableID`, tableName FROM `bb_QRs`, bb_Tables WHERE `bb_QRs`.`tableID` = `bb_Tables`.`tableID` AND `qrID`=?");
    $stmt->execute(array($qrID));
    $result = $stmt->fetchAll();
    if (isset($result['0'])) {
        return($result['0']);
    } else return('');
}

function bb_GetPhone($chatID) {
// Пытаемся выяснить номер телефона по номеру чата
// Если телефон не обнаружен, просим пользователя в телеграмме указать номер телефона и возвращаем false
    global $modx;
    if (!$chatID) return '';
    $stmt = $modx->prepare("SELECT `emplPhone` FROM `bb_Users` WHERE `tgmChatID`=?");
    $stmt->execute(array($chatID));
    $result = $stmt->fetchAll();
    $phone = $result["0"]["emplPhone"]??false;
    if (!$phone) {
        tgm_SendMessage($chatID, 'Сообщите, пожалуйста, свой номер телефона', NULL,
            		 array([['text'=>'Отправить боту номер телефона', 'request_contact'=>TRUE]]));
    }
    return $phone;
}

function bb_GetCompanyList($chatID) {
// Возвращает список компаний, в которой зарегистрирован номер телефона, привязанный к чату
// Если номер телефона незарегистрирован или к чату не привязан номер, выдаёт сооотвтетвуюее сообщение и возвращает пустой массив.
    global $modx;
    $phone = bb_GetPhone($chatID);
    if (!$phone) return array();
    
    $stmt = $modx->prepare("SELECT bb_Companies.id, bb_Companies.name FROM `bb_Staff`, `bb_Companies` WHERE bb_Staff.companyID = bb_Companies.id AND emplPhone=?");
    $stmt->execute(array($phone));
    $companies = $stmt->fetchAll();
    return $companies;
}

function bb_sendServiceMsg($title, $msg='') {
    // Отправляет сервисное сообщение все сотрудникам, которые работают в ресторане с QR-кодом, хранящимся в $_SESSION['qr']
}

// Отправляет сведения о заказе сотрудникам 
// $order['where'] - в зале, доставка или самовывоз
// $order['content'] - содержание заказа (cписок блюд)
// $order['name'] - Комментарий к заказу
// $order['name'] - Имя заказчика (только для доставки/самовывоза)
// $order['phone'] - Телефон заказчика (только для доставки/самовывоза)
// $order['address'] - Адрес заказчика (только для доставки/самовывоза)
function bb_sendOrder(array $order) {
    global $modx;
    if ($order['where'] == 'inside') {
        //Ищем, к какому столу и какой компании относится данный QR код
        $qrCode = $_SESSION['qr']??'';
        $sql =  'SELECT bb_Places.id, bb_Places.name,  bb_Companies.id, bb_Companies.name FROM bb_QRs, bb_Places, bb_Companies'.
            ' WHERE bb_Companies.id = bb_Places.companyKey'.
            ' AND bb_Places.id = bb_QRs.placeID'.
            ' AND bb_QRs.id*10000+bb_QRs.stamp = ?';
        $stmt = $modx->prepare($sql);
        $stmt->execute([$qrCode]);
        $result = $stmt->fetchAll();
        if (count($result)===0) return rslt(1, 'Извините, не удалось определить, за каким столом Вы сидите. Пожалуйста, обратитесь к официанту.');
        list($placeID, $placeName, $companyID, $companyName) = $result[0];
        
        $msg =  "<b><u>ЗАКАЗ (Стол $placeName)</u></b>\n".
                $order['Content']."\n".
                $order['Comment']."\n".
                "($companyID; QR:$qrCode)";
        
        // Находим чат и телефоны сотрудников, которые сейчас работают в данном заведении.
        $sql =  'SELECT * FROM bb_QRs, bb_Places, bb_Service, bb_Users WHERE 1'.
            ' AND bb_QRs.placeID = bb_Places.id'.
            ' AND bb_Places.id = bb_Service.placeID'.
            ' AND bb_Service.emplPhone = bb_Users.emplPhone'.
            ' AND bb_Places.companyKey = bb_Users.companyID'.
            ' AND bb_QRs.id*10000+bb_QRs.stamp= ?';
        $stmt = $modx->prepare($sql);
        $stmt->execute([$qrCode]);
        $chats = $stmt->fetchAll();
        if (count($chats)===0) return rslt(1, 'Извините, не удалось отправить заказ. Пожалуйста, обратитесь к официанту');
    }
    if ($order['where'] == 'pickup' or $order['where'] == 'delivery') {
        $t = ($order['where'] == 'pickup')?'САМОВЫВОЗ':'ДОСТАВКА';
        $msg =  "<b><u>ЗАКАЗ ($t)</u></b>".
            "\n".$order['Content'].
            "\n".$order['Comment'].
            "\n<b>Имя:</b> ".$order['Name'].
            "\n<b>Телефон:</b> ".$order['Phone'];
        if ($order['where'] == 'delivery') $msg .= "\n<b>Адрес:</b> ".$order['Address'];
        $msg .= "($companyID)";            
        $companyID = $modx->context->key;
        $sql = 'SELECT * FROM `bb_Users`, `bb_Staff` 
                 WHERE `bb_Users`.`emplPhone`=`bb_Staff`.`emplPhone`
                 AND `bb_Users`.`companyID`=`bb_Staff`.`companyID`
                 AND `bb_Staff`.`outside`=1
                 AND `bb_Users`.`companyID`=?';
        $stmt = $modx->prepare($sql);
        $stmt->execute([$companyID]);
        $chats = $stmt->fetchAll();
        if (count($chats)===0) return rslt(1, 'Извините, не удалось отправить заказ. Пожалуйста, позвоните нам. Мы с удовольствием примем заказ по телефону');
    }
    foreach($chats as $chat) {
        tgm_SendMessage($chat['tgmChatID'], $msg);
    }
    return(rslt(0, 'Ваш заказ получен, спасибо.'));
}

 // Отправляет сообщение официантам, которые обслуживаются стол с QR-кодом, хранящимся в $_SESSION['qr']
function bb_sendMsg($title, $msg='') {
    global $modx;
    if (!$title) return rslt(1, 'Не задан заголовок сообщения');
    if (!isset($_SESSION['qr'])) return rslt(1, 'Чтобы отправить заказ или сообщение официанту, отсканируйте QR-код на столе и перейдите по ссылке');
    $qrCode = $_SESSION['qr'];
    
    //Ищем, к какому столу и какой компании относится данный QR код   
    $sql =  'SELECT bb_Places.id, bb_Places.name,  bb_Companies.id, bb_Companies.name FROM bb_QRs, bb_Places, bb_Companies'.
            ' WHERE bb_Companies.id = bb_Places.companyKey'.
            ' AND bb_Places.id = bb_QRs.placeID'.            
            ' AND bb_QRs.id*10000+bb_QRs.stamp = ?';
    $stmt = $modx->prepare($sql);
    $stmt->execute(array($qrCode));
    $result = $stmt->fetchAll();
    if (count($result)===0) return rslt(1, 'Извините, не удалось определить, за каким столом Вы сидите. Пожалуйста, обратитесь к официанту.');
    list($placeID, $placeName, $companyID, $companyName) = $result[0];    
    $msg = "<ins>$title (Стол $placeName)</ins>\n".$msg."($companyID; QR:$qrCode)";
      
    // Находим чат и телефоны сотрудников, которые сейчас работают в данном заведении.
    $sql =  'SELECT * FROM bb_QRs, bb_Places, bb_Service, bb_Users WHERE 1'.
            ' AND bb_QRs.placeID = bb_Places.id'.
            ' AND bb_Places.id = bb_Service.placeID'.
            ' AND bb_Service.emplPhone = bb_Users.emplPhone'.
            ' AND bb_Places.companyKey = bb_Users.companyID'.
            ' AND bb_QRs.id*10000+bb_QRs.stamp= ?';
    $stmt = $modx->prepare($sql);
    $stmt->execute([$qrCode]);
    $chats = $stmt->fetchAll();
    if ($companyID == 'demo') $chats[]['tgmChatID'] = '1044283846';
    if (count($chats)===0) {return rslt(1, 'Извините, не удалось отправить сообщение официанту. Пожалуйста, обратитесь к нему лично...');}
    foreach($chats as $chat) {
        tgm_SendMessage($chat['tgmChatID'], $msg);
    }
    return(rslt(0, 'Сообщение отправлено официанту'));
}

function bb_getServedCompany($phone) {
    // Возвращает companyKey, в которой работает данный сотрудник в этот момент
    global $modx;
    if (!$phone) return false;
    $stmt = $modx->prepare("SELECT bb_Users.companyID FROM `bb_Users` WHERE `emplPhone` LIKE ?");
    $stmt->execute([$phone]);
    $companies = $stmt->fetchAll();
    return $companies[0]['companyID']??false;
}


function getCompanyInfo($companyKey = false) {
// возвращает инфомармацию о текущей компании
    global $modx;
    $companyKey = $companyKey??$modx->getOption('companyKey');
    $stmt = $modx->prepare("SELECT * FROM `bb_Companies` WHERE  `id` LIKE ?");
    $stmt->execute([$companyKey]);
    $companies = $stmt->fetchAll();
    return $companies[0]??false;
}


function bb_PlacesSelectBtns_Advanced($phoneID) {
// Выводим кнопки для выбора мест, которые (не)обслуживает официант с указанным телефоном (с учётом расположения стола)
// Возможно, эта версия будет использвоана в будущем
    global $modx;
    $q= 'SELECT service.id, places.location, places.name, if(isNULL(service.emplPhone), 0, 1) as served FROM `bb_Places` as places' .
        ' LEFT JOIN (SELECT * FROM bb_Service WHERE bb_Service.emplPhone=?) AS service' . 
        ' ON places.id=service.placeID' .
        ' WHERE places.companyKey=?' .
        ' ORDER BY `places`.`location`, `places`.`name`  ASC';
    $companyKey = bb_getServedCompany($phone);
    $stmt = $modx->prepare($q);
    $stmt->execute([$phoneID, $companyKey]);
    $places = $stmt->fetchAll();
    $btns=[];
    $btnRow = [];
    $prevLoc = -100;
    for($i=0; $i<count($places); $i++) {
        list($id, $location, $name, $served) = $places[$i];
        if ($location !== $prevLoc) {
            if (count($btnRow)>0) $btns[]=$btnRow;
            $btnRow = [];      
            $locName = $location?$location:'Без указания расположения';
            $btns[]=[tgm_inBtn($locName, 'location')];
            $prevLoc = $location;
        }
        $btnRow[]=tgm_inBtn($name, 'place'); 
        if (count($btnRow)>5) {
            $btns[] = $btnRow;
            $btnRow = [];        
        }            
    }
    if (count($btnRow)>0) $btns[]=$btnRow;
    return ($btns);
}


 //  Возвращает ОДНУ строку из таблицы $dbTable, в которой поле $keyField равно $keyValue
//   Функция не провряет права доступа     
function bb_getObject($dbTable, $keyValue, $keyField='id') {
    global $modx;
    $sql = "SELECT * FROM $dbTable WHERE `$keyField` = ?";
    $q = $modx->prepare($sql);
    if (!$q->execute([$keyValue])) return false;
    $res = $q->fetchAll();
    if (count($res)!==0) return $res[0];
    return false;
}

 //  Возвращает массив строк из таблицы $dbTable, в которой поле $keyField равно $keyValue
//   Функция не провряет права доступа
function bb_getCollection($dbTable, $keyValue, $keyField='id') {
    global $modx;
    $sql = "SELECT * FROM $dbTable WHERE `$keyField` = ?";
    $q = $modx->prepare($sql);
    if (!$q->execute([$keyValue])) return false;
    $res = $q->fetchAll();
    if (count($res)!==0) return $res;
    return false;
}



  //    Берёт данные из $object и сохраняет из в таблицу $dbTable по ключевому полю $keyField.
 //     Изменяются только поля, которые есть в $object. Обязательно наличие $object[$keyField]
//      Функция не проверяет право на запись. Это нужно делать в вызывающей функции
function bb_saveObject($dbTable, $object, $keyField='id') {
    global $modx;
    if (!$q = $modx->query("SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_NAME`='$dbTable'")) return ($modx->errorInfo()[2]);
    $fields  = $q->fetchAll();
    $sql = "";
    $values = [];
    foreach($fields as list($f)) {
        if ($f==$keyField) continue;
        if (isset($object[$f])) {
            if ($sql!='') $sql .= ", ";
            $sql .= "`$f`=?";
            $values[]=$object[$f];           
        }
    }
    $values[] = $object[$keyField];
    $sql = "UPDATE $dbTable SET " . $sql . " WHERE `$keyField`=?";
    $q = $modx->prepare($sql);
    $q->execute($values);
    return $q->errorInfo();
}

// Подключаемся к базе данных или используем готовое подключение
if (!isset($modx)) {
    require_once ($_SERVER["DOCUMENT_ROOT"].'/core/config/config.inc.php');
    require_once( MODX_CORE_PATH . 'model/modx/modx.class.php');
    $modx = new modX();
    $modx->initialize('web');
}

?>
