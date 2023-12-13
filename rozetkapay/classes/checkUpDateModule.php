<?php
class checkUpDateModule {
    
    private $commit = "36975d94df5f01159d5627a1211b4aa4304fd46b ";
    private $CMS_name = "prestashop-8.x";
    private $owner = "rozetkapay";
    
    public function _construct($owner, $CMS_name, $commit) {
        $this->owner = $owner;
        $this->CMS_name = $CMS_name;
        $this->commit = $commit;
    }
    
    public function AddFiles($dir = '') {

        if (empty($dir) && !empty($this->dir_sourse))
            $dir = $this->dir_sourse . '*';

        $files = glob($dir);

        foreach ($files as $file) {

            if (is_dir($file)) {
                if (in_array($file, $this->ignores))
                    continue;
                $this->AddFiles($file . '/*');
            } else
                $this->AddFile($file);
        }
    }
    
    public function checkPacFiles($files, $sha) {
        
    }
    
    public function checkUpData() {
        
        $comite = $this->getComiteLast();
        
        if($this->commit != $comite['sha']){
            return $comite;
        }
        
        return false;
        
    }
    
    public function getComiteLast() {
        
        $results = $this->getComites();
        
        $lastDate = 0;
        $comite = "";
        foreach ($results as $value) {
            $date = strtotime($value['commit']['committer']['date']);
            if($lastDate < $date){
                $lastDate = $date;
                $comite = $value;
            }
        }
        return $comite;
        
    }
    
    public function getComites() {
        
        try {
            $response = $this->sendRequest("https://api.github.com/repos/".$this->owner."/".$this->CMS_name."/commits");
        } catch (Exception $exc) {
            return false;
        }
        
        try {
            return json_decode($response->data, true);
        } catch (Exception $ex) {
            return false;
        }
        
    }
    
    public function sendRequest($url) {
    $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.100 Safari/537.36'
            ),
        ));

        $response = curl_exec($curl);

        $retval = new Response_data($curl, $response);

        curl_close($curl);

        return $retval;
    }
}

class Response_data {

    public $data;
    public $http_code;
    public $headers;
    public $ip;
    public $curlErrors;
    public $method;
    public $timestamp;

    public function __construct(&$curl = false, $response = null) {

        if ($curl !== false) {
            $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $headerCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $ip = curl_getinfo($curl, CURLINFO_PRIMARY_IP);
            $curlErrors = curl_error($curl);
            $this->data = ($response);
            $this->http_code = (int) $headerCode;
            $this->ip = $ip;
            $this->curlErrors = $curlErrors;
            $this->timestamp = date('h:i:sP d-m-Y');
        }
    }

}
