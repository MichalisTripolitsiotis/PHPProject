<?php
//start a session
session_start();
//store the key 
$key = "510eb7be47904818b8a174646202809";
//if user makes a request
if (isset($_REQUEST['city'])) {
    //store the city
    $city = $_REQUEST['city'];
    //make the request
    $url = "http://api.weatherapi.com/v1/current.json?key=" . $key . "&q=" . $city;
    //get the json response
    $json = file_get_contents($url);
    //decode the data
    $data = json_decode($json);
    //call the function to show this data
    getData($data);
    //save the requests of the user
    saveUserRequest();
    //save the responses of the API
    saveAPIRequest($data);
}


function getData($data)
{
    //get all the data that is needed
    $name = $data->location->name;
    $region = $data->location->region;
    $country = $data->location->country;
    $local_time = $data->location->localtime;
    $temp_c = $data->current->temp_c;
    $condition = $data->current->condition->text;
    //return an array to access the variables
    return array($name, $region, $country, $local_time, $temp_c, $condition);
}
//store them in a variable
$values = getData($data);
//I assume that I have to store the requests of the user
function saveUserRequest()
{
    $req_dump = print_r($_REQUEST, TRUE);
    $fp = fopen('requests.txt', 'a');
    fwrite($fp, $req_dump);
    fclose($fp);
}
//I assume that I want to store the API responses to a file
function saveAPIRequest($data)
{
    $req_name = print_r($data->location->name . " ", TRUE);
    $req_region = print_r($data->location->region . " ", TRUE);
    $req_country = print_r($data->location->country . " ", TRUE);
    $req_local_time = print_r($data->location->localtime . " ", TRUE);
    $req_temp_c = print_r($data->current->temp_c . " ", TRUE);
    $req_condition = print_r($data->current->condition->text . PHP_EOL, TRUE); //EOL for end of line
    $fp = fopen('APIrequests.txt', 'a');
    fwrite($fp, $req_name);
    fwrite($fp, $req_region);
    fwrite($fp, $req_country);
    fwrite($fp, $req_local_time);
    fwrite($fp, $req_temp_c);
    fwrite($fp, $req_condition);
    fclose($fp);
}
// IMPORTANT: the main problem here was that I could not iterate through the .txt files in order to get
//properly the values. I left it as it is in order to show you that I can read the data but not in a 
//responsive way.
function showUserRequests()
{
    $section = file_get_contents('requests.txt', FALSE);
    var_dump($section);
}
function showAPIRequests()
{
    echo file_get_contents('APIrequests.txt') . "<br>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="style/style.css">
    <title>Weather API</title>
    <title>Show results</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col">
                <br>
                <div class="card">
                    <div class="card-body text-center">
                        <h4 class="card-title"><?php echo $values[0] . ",<br> " . $values[1] . ",<br> " . $values[2] ?></h4>
                        <p class="card-text"><?php echo $values[3] ?></p>
                        <p class="card-text"><?php echo $values[4] . "&#8451;" ?></p>
                        <p class="card-text"><?php echo $values[5] ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p class="data"> <?php showUserRequests() ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p class="data"><?php showAPIRequests() ?></p>
            </div>
        </div>
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>

</html>
<?php
session_destroy();
?>