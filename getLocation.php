<?php
if(!empty($_POST['latitude']) && !empty($_POST['longitude'])){
    //Send request and receive json data by latitude and longitude
    $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($_POST['latitude']).','.trim($_POST['longitude']).'&sensor=false';
    $googlemapurl = 'https://maps.google.com/maps?q='.trim($_POST['latitude']).','.trim($_POST['longitude']).'';
    $coordinates ='&nbsp;'.trim($_POST['latitude']).'&#176;, '.trim($_POST['longitude']).'&#176;';
    $datetime = date('l jS \of F Y h:i:s A'); 
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    $referrer = $_SERVER['HTTP_REFERER'];
    $json = @file_get_contents($url);
    $data = json_decode($json);
    $status = $data->status;
    if($status=="OK"){
        //Get address from json data
        $location = $data->results[0]->formatted_address;
    }else{
        $location =  '';
    }
}
function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}

$user_ip = getUserIP();
$maincontent = '<div id="content">
    <div id="date">'. $datetime . '</div>
<div id="visitorip"><div id="visitoriplabel">IP Address:</div>' . $user_ip . '</div>
<div id="YourLocationlabel"> Your Location:</div>
<div id="address"><div id="addresslabel">Exact Address:</div>' . $location . '</div>
<div id="longitudeandlatitude"><div id="longitudeandlatitudelabel">GPS Coordinates</div> ' . $coordinates . ' </div>
<div id="useragent"><div id="useragentlabel">Device and Browser Info</div> ' . $useragent . '</div>
<div id="referlink"><div id="referlinklabel">Referrer URL:</div> ' . $referrer . '</div> 
<div id="map" class="mapiframe"><iframe width="1200"height="600" border="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$googlemapurl.'&amp;ie=UTF8&amp;&amp;output=embed"></iframe></div></div>';
echo $maincontent;    


// Create log line 
$logfileline = 'DATE: ' . $datetime . ' |IP ADDRESS: ' . $user_ip . ' |EXACT LOCATION ADDRESS: ' . $location . ' |EXACT LOCATION LONGITUDE & LATITUDE: ' . $coordinates . ' |EXACT LOCATION MAP LINK: ' . $googlemapurl .  ' |DEVICE AND BROWSER INFO: ' . $useragent . ' |REFERRER URL: ' . $referrer . "\n\n";
$logfile = 'logfile.txt'; 

// Open the log file in "Append" mode 
if (!$handle = fopen($logfile, 'a+')) { 
    die("Failed to open log file"); 
} 

// Write $logline to our logfile. 
if (fwrite($handle, $logfileline) === FALSE) { 
    die("Failed to write to log file"); 
} 
   
fclose($handle);

?>     

