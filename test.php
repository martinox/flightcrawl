<?php
require_once('FlightCrawler.php');

$flight  = '4U9763';
$lookupDate = '2016-03-15';

$flightCrawl = new FlightCrawl\FlightCrawler($flight, $lookupDate);

try{
	$result = $flightCrawl->crawlData(true, true);
} catch (Exception $e) {
	var_dump($e);
}

if($result === true){
	echo $flight.' has landed.';
}
else{
	echo $flight.' has not landed yet.';
}
echo "\n";
?>