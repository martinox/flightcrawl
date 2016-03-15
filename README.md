# FlightCrawl
FlightRadar HTML grabber for a flight

## Usage

```
require_once('FlightCrawler.php');

$flight  = '4U9763'; //Flightradar flight number
$lookupDate = '2016-03-15';

$flightCrawl = new FlightCrawl\FlightCrawler($flight, $lookupDate);
// return result and write result to status file
$result = $flightCrawl->crawlData(true, true);
```

If `$returnResult` is set to true, `$result` will be true if the flight has landed, otherwise it will be false.


