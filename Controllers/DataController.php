<?php 

namespace App\Controllers;


use GuzzleHttp;


Class Music
{

    var $token;
    var $client;
    var $id_art;

    public function __construct()
    {
        $this->client = new GuzzleHttp\Client();
    }

    public function generateToken(){
        /* Spotify Application Client ID and Secret Key */
        $client_id     = '01361280c0e144ea8647547d52b107e0';
        $client_secret = '56fe743894394331b4dc60a589127830';

        /* Get Spotify Authorization Token */
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,            'https://accounts.spotify.com/api/token' );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_POST,           1 );
        curl_setopt($ch, CURLOPT_POSTFIELDS,     'grant_type=client_credentials' );
        curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Authorization: Basic '.base64_encode($client_id.':'.$client_secret)));

        $result=curl_exec($ch);
        $json = json_decode($result, true);
        $this->token = $json['access_token'];
    }

    public function getArtistId($str){

        if(empty($str)){
            echo json_encode(array('error'=>'Debe ingresar un nombre para comenzar la busqueda'));die;
        }

        $str = str_replace(' ','',strtolower($str));

        $url = 'https://api.spotify.com/v1/search?q='.$str.'&type=artist';

        $res = $this->client->request('GET', $url ,[
            'headers'=> ['Authorization' => 'Bearer '.$this->token.'']]);
        $id_art = json_decode($res->getBody(), true);
        $this->id_art = $id_art['artists']['items'][0]['id'];
    }

    public function getAlbums(){

        $datos = array();

        $url = 'https://api.spotify.com/v1/artists/'.$this->id_art.'/albums';

        $res = $this->client->request('GET', $url ,[
            'headers'=> ['Authorization' => 'Bearer '.$this->token.'']]);
        $albums = json_decode($res->getBody(), true);

        for ($x = 0; $x <= count($albums['items'])-1; $x++) {
            $datos[] = array(
                "name"=>$albums['items'][$x]['name'],
                "released"=>$albums['items'][$x]['release_date'],
                "tracks"=>$albums['items'][$x]['total_tracks'],
                "cover"=> array(
                    "height"=>$albums['items'][$x]['images'][0]['height'],
                    "width"=>$albums['items'][$x]['images'][0]['width'],
                    "url"=>$albums['items'][$x]['images'][0]['url']
                ));
        }

        echo json_encode($datos);

    }
        
}
