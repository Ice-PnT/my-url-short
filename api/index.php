<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: *");
  header("Access-Control-Allow-Methods: *");

  require_once("../db/urls.php");
  require_once("../lib/phpqrcode/qrlib.php");

  $data = [];
  $path = explode('/', $_SERVER['REQUEST_URI']);
  $key = $path[count($path)-1];

  $method = $_SERVER['REQUEST_METHOD'];

  switch($method) {
      case "GET":
          if($key){
            $sql = "SELECT url_key, url_full, url_short_datetime, (SELECT count(*) FROM urls_load WHERE load_url_key = url_key ) AS url_count_load FROM urls WHERE 1 ";
            if($key!="ALL") $sql .= " AND url_key = :urlkey";
            $sql .= " ORDER BY url_short_datetime";
            
            $sth = $URL_DB->prepare($sql);
            $sth->bindParam(':urlkey', $key);
            $sth->execute();

            if($key=="ALL"){
              while($row = $sth->fetch(PDO::FETCH_ASSOC)){
                $data[] = $row;
              }
            }else{
              $data = $sth->fetch(PDO::FETCH_ASSOC);
              
              $sql = "SELECT * FROM urls_load WHERE load_url_key = :url_key";
              $sth = $URL_DB->prepare($sql);
              $sth->execute(array(':url_key' => $key));
              while($row = $sth->fetch(PDO::FETCH_ASSOC)){
                $data['DATA_LOAD'][] = $row;
              }
            }
          }
          echo json_encode($data);
      break;

      case "POST":

          $urlpost = json_decode( file_get_contents('php://input') );

          $url_full = $urlpost->url_full;
          $url_key = $urlpost->url_key;

          $sql = "SELECT url_key, url_full, url_short_datetime, (SELECT count(*) FROM urls_load WHERE load_url_key = url_key ) AS url_count_load FROM urls WHERE url_full = :url_full ";
          $where =  array();
          $where[':url_full'] = $url_full;
          if($url_key){
            $sql .= " AND url_key = :url_key";
            $where[':url_key'] = $url_key;
          }
          $sth = $URL_DB->prepare($sql);
          $sth->execute($where);
          $data = $sth->fetch(PDO::FETCH_ASSOC);

          if(!$data||count($data) === 0){
            if(!$url_key) $url_key = substr(md5(microtime()), rand(0, 26), 5);
            $url_short_datetime = date("Y-m-d H:i:s");

            $sql = "SELECT url_key FROM urls WHERE url_key = :url_key";
            $sth = $URL_DB->prepare($sql);
            $sth->execute(array(':url_key' => $url_key));
            $ckey = $sth->fetch(PDO::FETCH_ASSOC);

            if($ckey&&$ckey['url_key'] === $url_key){
              $data['ERROR'] = "KEY_ERROR";
            }else{
              $sql = "INSERT INTO urls (url_key, url_full, url_short_datetime) VALUES (:url_key, :url_full, :url_short_datetime)";
              $sth = $URL_DB->prepare($sql);
              if($sth->execute(array(':url_key' => $url_key, ':url_full' => $url_full, ':url_short_datetime' => $url_short_datetime))){

                $filepath = "../image/qr/".$url_key.".png";
                $data = "https://myurlshort.com/".$url_key;
                QRcode::png($data, $filepath, 'L', 10, 2);

                $data = array('url_key'=>$url_key, 'url_full'=>$url_full, 'url_short_datetime'=>$url_short_datetime, 'url_count_load'=>0);
              }else{
                $data['WARNING'] = "DATA_EXIST";
              }
            }
          }else{
            $data['ERROR'] = "DATA_ERROR";
          }
          echo json_encode($data);
      break;
  }