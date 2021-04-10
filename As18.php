<?php
echo "<a target='_blank' href='https://github.com/jacobpapesh/api.git'>GitHub repo</a>";


class CountryDeath{
    public $death;
    public $country;

    public function __construct($death, $country){
        $this->country = $country;
        $this->death = $death;
    }

    public function __toString(){
        return $this->country . " has " . $this->death . " deaths<br>";
    }
}

main();

#-----------------------------------------------------------------------------
# FUNCTIONS
#-----------------------------------------------------------------------------
function main () {
	
	$apiCall = 'https://api.covid19api.com/summary';
	// line below stopped working on CSIS server
	// $json_string = file_get_contents($apiCall); 
	$json_string = curl_get_contents($apiCall);
	$obj = json_decode($json_string);


    $array = Array();
    foreach($obj->Countries as $i) {
        $countryDeath = new CountryDeath($i->TotalDeaths, $i->Country);
        array_push($array,$countryDeath);
    }

    array_multisort($array,SORT_DESC);
    echo "<br><br>";

    echo "<table>";
    echo "<tbody>";
    echo "<tr>";
    echo "<td>Country</td>";
    echo "<td>Deaths</td>";
    echo "</tr>";
    for ($i=0; $i<10; $i++){
        echo "<tr>";
        echo "<td>" . $array[$i]->country . "</td>";
        echo "<td>" . $array[$i]->death . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
}




#-----------------------------------------------------------------------------
// read data from a URL into a string
function curl_get_contents($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

?>

