<?php
/**
* Language Helper
*
* UserCandy PHP Framework
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version UC 2.0.0.0
*/

namespace Helpers;

use Models\{TrailersModel};

class Trailers { 

    // Connect to spireon.com api and get trailer data by trailer number
    public static function getTrailerData($trailer=null){
        if(!empty($trailer)){

            // Connect to Spireon API via Curl
            $url = "https://api.us.spireon.com/api/assetStatus?name=$trailer";
            // $username = "MilanSCapi";
            // $password = "MiLaNaPi2016";
            $username = "";
            $password = "";

            // $payload = [
            //     'code'=>$discordCode,
            // ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(   
                'Accept: application/json',
                'Content-Type: application/json')                                                           
            ); 
            $result = curl_exec($ch);
            curl_close($ch);  

            $output = json_decode($result, true);

            if(!empty($output) && !empty($output["msg"])){
                return $output;
            }else if(!empty($output) && !empty($output['count']) && $output['count'] > 0){
                if($trailer == $output['data'][0]['name']){
                    return $output['data'][0];
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
    }

    // Connect to spireon.com api and get all trailers data and update in database
    public static function updateAllTrailersDB($set = "FLEETLOCATE", $sendToBot = false){
        // Load Trailer Model
        $trailersModel = new TrailersModel();

        // Connect to Spireon API via Curl
        $url = "https://api.us.spireon.com/api/assetStatus?max=5000";
        // $username = "MilanSCapi";
        // $password = "MiLaNaPi2016";
        if($set === "FLEETLOCATE"){
          $username = "";
          $password = "";
        }else if($set === "FLEETLOCATE2"){
          $username = "";
          $password = "";
        }

        // $payload = [
        //     'code'=>$discordCode,
        // ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(   
            'Accept: application/json',
            'Content-Type: application/json')                                                           
        ); 
        $result = curl_exec($ch);
        curl_close($ch);  

        //var_dump($result);

        $output = json_decode($result, true);
        $i = 0;
        $totalUpdated = 0;

        if(!empty($output) && !empty($output["msg"])){
            return "Error Getting Data";
        }else if(!empty($output) && !empty($output['count']) && $output['count'] > 0){
            $updatedArray = array();
            foreach($output['data'] as $od){
                $cargoStatus = (!empty($od['cargoLoaded'][0]['cargoSensor1'])) ? $od['cargoLoaded'][0]['cargoSensor1'] : "";
                // Update Trailer data
                if(date('Y-m-d',strtotime($od['eventDateTime'])) === date('Y-m-d')){
                  if($set === "FLEETLOCATE2"){
                    $odName = str_replace("PT", "", $od['name']);
                  }else{
                    $odName = $od['name'];
                  }
                  if($trailersModel->updateTrailerTracking($odName,$od['stoppedStartTime'],$od['movingStartTime'],$od['moving'],$od['speed'],$od['address'],$od['city'],$od['state'],$od['zip'],$cargoStatus,$od['lat'],$od['lng'],$od['eventDateTime'])){
                    $totalUpdated++;
                    if(!empty($od['eventDateTime'])){
                      array_push($updatedArray, $od);
                      // Add tracking history for trailer
                      $trailersModel->addTrailerTrackingHistory($od['name'],$od['lat'],$od['lng'],$od['eventDateTime']);
                    }
                  }
                }
                $i++;
            }
            if($sendToBot){
              return $updatedArray;
            }else{
              return "Fleet Locate Data Updated<hr>Total Updated: ".$totalUpdated."<hr>Total Trailers: ".$i;
            }
        }else{
          if($sendToBot){
            return false;
          }else{
            return "Error Getting Data: Unknown";
          }
        }
    }

    // Connect to lbtelematics.net api and get trailer data by trailer number
    public static function getTrailerDataLBT($trailer=null){
        if(!empty($trailer)){

            $trailersModel = new TrailersModel();

            // get unit id for lbt
            $getAltTrailer = $trailersModel->getAltTrailer($trailer);

            if(empty($getAltTrailer)) return false;

            // Connect to lbtelematics.net via Curl
            $url = "https://www.lbtelematics.net/track1/Track";
            $url2 = "https://www.lbtelematics.net/track1/Track?page=map.device.last&page_cmd=mapupd&device={$getAltTrailer}&limit=1&limType=last";
            $account = "lbt-3089";
            $username = "kmoore";
            $password = "milan1";

            $cookie_file_path = ROOTDIR."/temp/cookies/";
            $ckfile = tempnam($cookie_file_path, "CURLCOOKIE");

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,"account=$account&user=$username&password=$password&locale=en&submit=Login");
            curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $ckfile);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($ch);

            curl_setopt($ch, CURLOPT_URL, $url2);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
            curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 100);
            //curl_setopt($ch, CURLOPT_POSTFIELDS, $POST_DATA);
            $response = curl_exec($ch);
            curl_close($ch);
            $output = json_decode($response, true);

            //var_dump('lastupdate',$output["JMapData"]["LastEvent"]["date"]." ".$output["JMapData"]["LastEvent"]["time"]);
            //echo "<hr>";
            //var_dump($output["JMapData"]);
            //echo "<hr>";
            if (!empty($output["JMapData"]["DataSets"][0]['Points'][0])) {
              $trackingData = explode("|", $output["JMapData"]["DataSets"][0]['Points'][0]);
            }else{
              return false;
            }
            //var_dump('lat/long',$trackingData[9],$trackingData[10]);
            //echo "<hr>";
            $addy = explode(",",$trackingData[21]);
            //var_dump('address',$addy[0]);
            //echo "<hr>";
            //var_dump('city',$addy[1]);
            //echo "<hr>";
            $stateZip = (!empty($addy[2])) ? explode(" ",$addy[2]) : "";
            //var_dump($stateZip);
            //echo "<hr>";
            //var_dump('state',$stateZip[1]);
            //echo "<hr>";
            //var_dump('zip',$stateZip[2]);
            //echo "<hr>";
            //var_dump($trackingData);

            // Convert to useable date
            
            $date = date("Y-m-d", strtotime($output["JMapData"]["LastEvent"]["date"]));

            if(!empty($output) && $date === date('Y-m-d')){
                $lbTrailerData = [];
                $lbTrailerData['address'] = str_replace('"',"",$addy[0]);
                $lbTrailerData['city'] = (!empty($addy[1])) ? $addy[1] : "";
                $lbTrailerData['state'] = (!empty($stateZip[1])) ? $stateZip[1] : "";
                $lbTrailerData['zip'] = (!empty($stateZip[2])) ? $stateZip[2] : "";
                $lbTrailerData['lat'] = $trackingData[9]; 
                $lbTrailerData['lng'] = $trackingData[10]; 
                $lbTrailerData['eventDateTime'] = $date." ".$output["JMapData"]["LastEvent"]["time"];
            }

            if(!empty($lbTrailerData)){
                $trailersModel->updateTrailerTracking($trailer,null,null,null,null,$lbTrailerData['address'],$lbTrailerData['city'],$lbTrailerData['state'],$lbTrailerData['zip'],null,$lbTrailerData['lat'],$lbTrailerData['lng'],$lbTrailerData['eventDateTime']);
                $trailersModel->addTrailerTrackingHistory($trailer,$lbTrailerData['lat'],$lbTrailerData['lng'],$lbTrailerData['eventDateTime']);
                return $lbTrailerData;
            }else{
                return false;
            }
        }
    }

    // Connect to insight.skybitz.com api and get trailer data by trailer number
    public static function getTrailerDataSkyBitz($trailer=null,$site=null){

        if(!empty($trailer)){

            $trailersModel = new TrailersModel();

            if($site == "skybitz"){
              $username = "kmoore@milanexpress.com";
              $password = "Welcome123";
            }else if($site == "skybitz1"){
              $username = "kmoore@milanexpress.com1";
              $password = "Welcome123";
            }

            // Connect to lbtelematics.net via Curl
            $url = "https://insight.skybitz.com/CheckAccess";
            //$url2 = "https://insight.skybitz.com/assetsView;jsessionid=x1JVuObXNG39yrdp3zoYz_sVr7fmw3nI5HKScj2B3bp90vBM_XoS!-1104885457?query=false&opType=8193";
            $url2 = "https://insight.skybitz.com/LAABSearch?event=menuSearchAssets&requestorUrl=/LAABSearch?event=menustartsearch&dispatchTo=/LocateAssets/NewAdvAssetSearchResults.jsp&map=no&optMulTerminal=asn&requestorMenu=locateAsset";

            $cookie_file_path = ROOTDIR."/temp/cookies/";
            $ckfile = tempnam($cookie_file_path, "CURLCOOKIE");

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,"strUserName=$username&strPassword=$password&action=/CheckAccess");
            curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $ckfile);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($ch);

            curl_setopt($ch, CURLOPT_URL, $url2);
            curl_setopt($ch, CURLOPT_POST, 1);
            //curl_setopt($ch, CURLOPT_POSTFIELDS,"assetId=$trailer");
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
            curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 100);
            //curl_setopt($ch, CURLOPT_POSTFIELDS, $POST_DATA);
            $response = curl_exec($ch);
            curl_close($ch);
            //$output = json_decode($response, true);
            $dom = new \DOMDocument();
            @$dom->loadHTML($response, LIBXML_NOERROR);
            $dom->preserveWhiteSpace = false;
            $tables = $dom->getElementsByTagName('table');
            $rows = $tables->item(12)->getElementsByTagName('tr');
            //var_dump($response);
            $trailerOutputArray = array();
            $i = 0;
            foreach($rows as $row){
              if($i != 0){
                $curTrailerData = explode(PHP_EOL,trim($row->textContent));
                if(sizeof($curTrailerData) > 1){
                  $curTrailerArray = array(
                    'name'=>$curTrailerData[0], 
                    'trailer'=>$curTrailerData[0], 
                    'eventDateTime'=>date('Y-m-d H:i:s',strtotime($curTrailerData[1])), 
                    'lat'=>$curTrailerData[4],
                    'lng'=>$curTrailerData[5],
                    'address'=>$curTrailerData[10],
                    'city'=>$curTrailerData[6],
                    'state'=>$curTrailerData[7]
                  );
                  // Check if update date is today
                  if(date('Y-m-d',strtotime($curTrailerData[1])) === date('Y-m-d')){
                    $zip = $trailersModel->getZipForCityState($curTrailerArray['city'], $curTrailerArray['state']);
                    if($trailersModel->updateTrailerTracking($curTrailerArray['trailer'],null,null,null,null,$curTrailerArray['address'],$curTrailerArray['city'],$curTrailerArray['state'],$zip,null,$curTrailerArray['lat'],$curTrailerArray['lng'],$curTrailerArray['eventDateTime'])){
                      // Add trailer to array if updated so bot can crate embed
                      array_push($trailerOutputArray,$curTrailerArray);
                      // Add tracking history for trailer
                      $trailersModel->addTrailerTrackingHistory($curTrailerArray['trailer'],$curTrailerArray['lat'],$curTrailerArray['lng'],$curTrailerArray['eventDateTime']);
                    }
                  }
                }
              }
              $i++;
            }            
            //var_dump($trailerOutputArray);

            if(!empty($trailerOutputArray)){
                return $trailerOutputArray;
            }else{
                return false;
            }
        }
    }

    // Connect to starleasing.com api and get trailer data by trailer number
    public static function getTrailerDataXtra(){

      $trailersModel = new TrailersModel();

      $username = "";
      $password = "";

      // Connect to lbtelematics.net via Curl
      $url = "https://secure.xtralease.com/login";
      $url2 = "https://secure.xtralease.com/trailers";

      $cookie_file_path = ROOTDIR."/temp/cookies/";
      $ckfile = tempnam($cookie_file_path, "CURLCOOKIE");

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,$url);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS,"login=$username&password=$password");
      curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);
      curl_setopt($ch, CURLOPT_COOKIEFILE, $ckfile);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
      $result = curl_exec($ch);

      curl_setopt($ch, CURLOPT_URL, $url2);
      curl_setopt($ch, CURLOPT_POST, 1);
      //curl_setopt($ch, CURLOPT_POSTFIELDS,"assetId=$trailer");
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
      curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
      curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
      curl_setopt($ch, CURLOPT_TIMEOUT, 100);
      //curl_setopt($ch, CURLOPT_POSTFIELDS, $POST_DATA);
      $response = curl_exec($ch);
      curl_close($ch);

      //var_dump($response);die;

      //$output = json_decode($response, true);
      $dom = new \DOMDocument();
      @$dom->loadHTML($response, LIBXML_NOERROR);
      $dom->preserveWhiteSpace = false;
      $tables = $dom->getElementsByTagName('table');
      $rows = $tables->item(12)->getElementsByTagName('tr');
      //var_dump($response);
      $trailerOutputArray = array();
      $i = 0;
      foreach($rows as $row){
        if($i != 0){
          $curTrailerData = explode(PHP_EOL,trim($row->textContent));
          if(sizeof($curTrailerData) > 1){
            $curTrailerArray = array(
              'name'=>$curTrailerData[0], 
              'trailer'=>$curTrailerData[0], 
              'eventDateTime'=>date('Y-m-d H:i:s',strtotime($curTrailerData[1])), 
              'lat'=>$curTrailerData[4],
              'lng'=>$curTrailerData[5],
              'address'=>$curTrailerData[10],
              'city'=>$curTrailerData[6],
              'state'=>$curTrailerData[7]
            );
            // Check if update date is today
            if(date('Y-m-d',strtotime($curTrailerData[1])) === date('Y-m-d')){
              $zip = $trailersModel->getZipForCityState($curTrailerArray['city'], $curTrailerArray['state']);
              if($trailersModel->updateTrailerTracking($curTrailerArray['trailer'],null,null,null,null,$curTrailerArray['address'],$curTrailerArray['city'],$curTrailerArray['state'],$zip,null,$curTrailerArray['lat'],$curTrailerArray['lng'],$curTrailerArray['eventDateTime'])){
                // Add trailer to array if updated so bot can crate embed
                array_push($trailerOutputArray,$curTrailerArray);
                // Add tracking history for trailer
                $trailersModel->addTrailerTrackingHistory($curTrailerArray['trailer'],$curTrailerArray['lat'],$curTrailerArray['lng'],$curTrailerArray['eventDateTime']);
              }
            }
          }
        }
        $i++;
      }            
      //var_dump($trailerOutputArray);

      if(!empty($trailerOutputArray)){
          return $trailerOutputArray;
      }else{
          return false;
      }
    }

    // Get all Friday dates for set year
    public function getFridays($year){
      date_default_timezone_set('America/New_York');

      $fridays = array();
      $dt = strtotime("{$year}-01-01 Friday");
      $wk = 0;
      $d  = date('j', $dt);
  
      while ($wk < 52) {
          $fridays[] = $dt;
          $wk++;
          $d += 7;
          $dt = mktime(0, 0, 0, 1, $d, $year);
      }
  
      return $fridays;
    }

}