<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
// echo '<pre>';
// print_r($_POST);exit;
$first_name = $_POST['f_name'];
$last_name = $_POST['f_name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$url = $_POST['site'];
$ip = $_SERVER['REMOTE_ADDR'];
$land = $_POST['land'];
$data_ip = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
$country = $data_ip->country;



function auth() {

  $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://affiliate.cedcapital.live/api/affiliate/generateauthtoken',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "userName": "Griffon_ruEU",
    "password": "dewfrvg345v3",
    "AffiliateId": "Nalivaika_RuEU",
    "CampaignId": "499"
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$auth = json_decode($response);

$token = $auth->token;

return $token;

}

$token = auth();



function createLead() {
  global $token, $first_name, $last_name, $email, $phone, $country, $land;
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://affiliate.cedcapital.live/api/aff/accounts',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_VERBOSE => true,
    CURLOPT_POSTFIELDS =>'{
    "FirstName": "'.$first_name.'",
    "LastName": "'.$last_name.'",
    "Phone": "'.$phone.'",
    "Email": "'.$email.'",
    "Country": "'.$country.'",
    "AffiliateId": "Griffon_ruEU",
    "CampaignId": "'.$land.'"
  }',
    CURLOPT_HTTPHEADER => array(
      'AuthToken: '.$token,
      'Content-Type: application/json'
    ),
  ));

  $response = curl_exec($curl);

  curl_close($curl);
  return $response;
}

$createLead = createLead();


function sendTelegram() {
  global $token, $first_name, $last_name, $email, $phone, $createLead, $url, $country;
  
  
  $token = "5320504553:AAH3oYyZQS25yTbsyl3OjDFi6k-A7JOPmV8";
  $chat_id = "-1001850664628";
  
  
  $arr = array(
      '<b>InvestClub (KEITARO)</b>' => '',
      'Имя: ' => $first_name,
      'Фамилия: ' => $last_name,
      'Телефон: ' => $phone,
      'Email: ' => $email,
      'Страна: ' => $country,
      'url' => $url,
      'Детали статуса' => $createLead
  );


   

  $txt = '';
  foreach($arr as $key => $value) {
    $txt .= "<b>".$key."</b> ".$value."%0A";
  };
  
  $sendToTelegram = fopen("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$txt}","r");


}

sendTelegram();
var_dump($_POST['fbpixel']);

header('Location: thanks.php?fbpixel='.$_POST['fbpixel'].'&yandex='.$_POST['yandex']);