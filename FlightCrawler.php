<?php namespace FlightCrawl;

class FlightCrawler{

    const BASEURL = 'http://www.flightradar24.com/data/flights/';
    
    private $debug = false;

    private $targetFile = 'status.php';
    private $datePatternPre = '#<tr.*?data-date="';
    private $datePatternPost = '".*?>(.*?)<\/tr>#s';
    private $pattern = '#<td>.*?<span.*?"></span>(.*?)</td>#s';
    private $preOutput = '<?php //';

    public $flight;
    public $lookupDate;

    public $hasLanded;

    private $content;

    public function __construct($flight, $lookupDate){
        $this->flight = $flight;
        $this->lookupDate = $lookupDate;
    }

    public function debug($enable){
        $this->debug = $enable;
    }

    public function crawlData($returnResult=false, $writeResult=true){
        $this->hasLanded = false;
        $flight_url = FlightCrawler::BASEURL.$this->flight;

        if($this->debug){
            echo $flight_url;
        }

        $this->content = file_get_contents($flight_url);

        if($this->content !== false){
            $cut_content = '';

            //cut part from our lookup date
            $data_date_pos = stripos($this->content, 'data-date="'.$this->lookupDate.'"');
            if($data_date_pos !== false){
                $tr_end_pos = stripos($this->content, '</tr>', $data_date_pos);
                if($tr_end_pos !== false){
                    $cut_content = substr($this->content, $data_date_pos, ($tr_end_pos-$data_date_pos));
                }
            }

            if($cut_content != ''){
                $matches = array();
                if(preg_match($this->pattern, $cut_content, $matches) !== false){
                    if(count($matches) >= 2){
                        $status = trim($matches[1]);
                        if($matches[1] != '' && stripos($matches[1], 'Landed') !== false){
                            $this->hasLanded = true;
                        }
                    }
                }

            }
        }

        if($writeResult){
            $this->writeStatus();
        }

        if($returnResult){
            return $this->hasLanded;
        }
    }

    private function writeStatus(){
        if($this->targetFile != '' && is_file($this->targetFile)){
            //append date for debbuging
            $output = $this->preOutput.' '.date('Y-m-d H:i:s');
            $output .= PHP_EOL;
            $output .= '$has_landed='.($this->hasLanded?'true':'false').';'.PHP_EOL;
            if(file_put_contents($this->targetFile, $output) !== false){

            }
            else{

            }
        }
    }
}
?>