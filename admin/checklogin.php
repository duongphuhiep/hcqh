<?php
require_once("lib/ChromePhp.php");

function checkLogin($idtoken)
{
    $ch = curl_init("https://www.googleapis.com/oauth2/v3/tokeninfo");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array("id_token" => $idtoken)));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //otherwise, the curl_exec will forward the page to googleapis.com
    $dataString = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($dataString, true);
    //var_dump($data[array("aud")]);
    if ($data["aud"] == "786362358731-q3s0lph8krhk90sc2bp1eujokfjbburt.apps.googleusercontent.com") {
        return $data;
    }
    return null;
}

$idtoken = $_POST["idtoken"];
$data = checkLogin($idtoken);
if ($data == null) {
    header('HTTP/1.0 401 Unauthorized');
}
else {
    $googleUserId = $data["sub"];
    $email = $data["email"];
    echo ((string)$googleUserId) . " (" . $email . ")";
}