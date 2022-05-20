<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
date_default_timezone_set('Asia/Kolkata');
error_reporting(E_ALL);
//include 'config.php';
$memcache = new Memcache;
$memcache->addServer("127.0.0.1");
$myfile = fopen("matchdata.txt", "r") or die("Unable to open file!");
$data = @fread($myfile, filesize("matchdata.txt"));
fclose($myfile);
$arrfile = json_decode($data, true);
//print_r($MstCode);
$matchArr = [];
//print_r($arrfile);
/* Get Token*/
$lotusfile = fopen("score-action.txt", "r") or die("Unable to open file!");
$tokenData = @fread($lotusfile, filesize("score-action.txt"));
fclose($lotusfile);
$statusAction = json_decode($tokenData, true);
print_r($statusAction);
if ($statusAction['status'] != '1')
    return ($statusAction['status'] == '1') ? 'Activated' : 'Deactived';
/*End Token*/


if (is_array($arrfile) && array_key_exists('url', $arrfile)) {
    foreach ($arrfile['ids'] as $val) {
//$res=get_web_page('https://member.play7s.com/api/match-center/stats/4/'.$val['match_id']);
        $res = get_web_page('http://139.59.68.148:8082/scoreRam?eventid=' . $val['match_id']);
        $memcache->set('score_' . $val['match_id'], $res['content'], false, 60);
//print_r($memcache->get('score_'.$val['match_id']));
        $matchArr[] = $res['content'];
    }
}

print_r($matchArr);

function get_web_page($url)
{
    $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING => "",       // handle all encodings
        CURLOPT_USERAGENT => "spider", // who am i
        CURLOPT_AUTOREFERER => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 5,      // timeout on connect
        CURLOPT_TIMEOUT => 10,      // timeout on response
        CURLOPT_MAXREDIRS => 10,       // stop after 10 redirects
        CURLOPT_SSL_VERIFYPEER => false     // Disabled SSL Cert checks
    );

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

    $headers = array();
    $headers[] = 'X-Client-Info: b572eae027f25def87d996a5f361be7e';
    $headers[] = 'Cookie: _ga=GA1.2.928582552.1545296557; _gid=GA1.2.608882473.1554121757';
    $headers[] = 'X-App-Version: 3.6.4.9';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: en-US,en;q=0.9';
    $headers[] = 'Authorization: eyJhbGciOiJIUzI1NiJ9.eyJtZW1iZXJDb2RlIjoiTElPRDFQMDFNMDEiLCJsYXN0TG9naW5UaW1lIjoxNTU0MTIzNDA2MTI4LCJuYmYiOjE1NTQxMjM0MDQsImxvZ2luTmFtZSI6IjAxc2FpIiwibG9naW5JUCI6IjEwMy42OC40Mi4xNzciLCJsb2dpbkNvdW50cnkiOiJJTiIsInRoZW1lIjoibG90dXMiLCJleHAiOjE1NTQxMjcwMDYsImlhdCI6MTU1NDEyMzQwNiwibWVtYmVySWQiOjkyNjMsInVwbGluZXMiOnsiQ09ZIjp7InVzZXJJZCI6MSwidXNlckNvZGUiOiJhZG1pbi51c2VyIn0sIlNNQSI6eyJ1c2VySWQiOjIyNTEsInVzZXJDb2RlIjoiTElPRCJ9LCJNQSI6eyJ1c2VySWQiOjg0OTMsInVzZXJDb2RlIjoiTElPRDFQIn0sIkFnZW50Ijp7InVzZXJJZCI6OTAzNCwidXNlckNvZGUiOiJMSU9EMVAwMSJ9fSwiYWxsb3dTaGFrdGlQcm8iOmZhbHNlfQ.XQMBNnbcNJpz_zkwkQ7nUrmjbdokw4wyK6IGEqKHkX4';
    $headers[] = 'X-Xid: 877e77c1-99f4-4474-a182-612b53cd4ae0';
    $headers[] = 'Accept: application/json, text/plain, */*';
    $headers[] = 'Referer: https://www.lotusbook.com/d/index.html';
    $headers[] = 'X-Client-Id: 928582552.1545296557';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.86 Safari/537.36';
    $headers[] = 'Connection: keep-alive';
    $headers[] = 'X-Client: desktop';
    $headers[] = 'X-User-Id: LIOD1P01M01';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt_array($ch, $options);
    $content = curl_exec($ch);
    $err = curl_errno($ch);
    $errmsg = curl_error($ch);
    $header = curl_getinfo($ch);
    curl_close($ch);

    $header['errno'] = $err;
    $header['errmsg'] = $errmsg;
    $header['content'] = $content;
    return $header;
}

die;
?>