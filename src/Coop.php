<?php

namespace Amunyua\Coop;

use Carbon\Carbon;
use Ixudra\Curl\Facades\Curl;
use Symfony\Component\Dotenv\Dotenv;

/**
 * Class Coop
 * @package Amunyua\Coop
 */
class Coop
{
    public static function env($env_param){

        $dotenv = new Dotenv();

//        $dotenv->load('../.env');
        $dotenv->load('.env');
//        $dotenv->stor('../.env');

        $env = getenv($env_param);

        return $env;
    }

    /**
     * @return mixed
     */
    public static function generateLiveToken(){

        try {
            $consumer_key = env("C_CONSUMER_KEY");
            $consumer_secret = env("C_CONSUMER_SECRET");
        } catch (\Throwable $th) {
            $consumer_key = self::env("C_CONSUMER_KEY");
            $consumer_secret = self::env("C_CONSUMER_SECRET");
        }

        if(!isset($consumer_key)||!isset($consumer_secret)){
            die("please declare the consumer key and consumer secret as defined in the documentation");
        }
        $url = 'http://developer.co-opbank.co.ke:8280/token?grant_type=client_credentials';
        $credentials = base64_encode($consumer_key.':'.$consumer_secret);
        $header = array("Authorization: Basic ".$credentials);
        $content = "grant_type=client_credentials";
        $curl = curl_init();

        curl_setopt_array($curl, array(

            CURLOPT_URL => $url,

            CURLOPT_HTTPHEADER => $header,

            CURLOPT_SSL_VERIFYPEER => false,

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_POST => true,

            CURLOPT_POSTFIELDS => $content

        ));

        $response = curl_exec($curl);
        return json_decode($response)->access_token;
    }


    /**
     * use this function to generate a sandbox token
     * @return mixed
     */
    public static function generateSandBoxToken(){

        try {
            $consumer_key = self::env("C_CONSUMER_KEY");
//            $consumer_key = env("C_CONSUMER_KEY");
            $consumer_secret = env("C_CONSUMER_SECRET");
//            $consumer_key = 'Ek4deBXb4TpGxXjmtSBBggm9Rwka';
//            $consumer_secret ="0QjSMnlHBfCgBoXrsw0uh1Fg3fUa";
        } catch (\Throwable $th) {
            $consumer_key = self::env("C_CONSUMER_KEY");
            $consumer_secret = self::env("C_CONSUMER_SECRET");
        }
        dd($consumer_key);

        if(!isset($consumer_key)||!isset($consumer_secret)){
            die("please declare the consumer key and consumer secret as defined in the documentation");
        }
        $url = 'https://developer.co-opbank.co.ke:8243/token';
        $credentials = base64_encode($consumer_key.':'.$consumer_secret);
        $header = array("Authorization: Basic ".$credentials);
        $content = "grant_type=client_credentials";
        $curl = curl_init();

        curl_setopt_array($curl, array(

            CURLOPT_URL => $url,

            CURLOPT_HTTPHEADER => $header,

            CURLOPT_SSL_VERIFYPEER => false,

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_POST => true,

            CURLOPT_POSTFIELDS => $content

        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $token =json_decode($response)->access_token;
//        print_r($response);
        return $token;
    }


    /**
     * @param string $accountNumber
     * @return array|mixed|\stdClass
     */
    public static function getAccountBalance($accountNumber = '36001873027'){
        //set the endpoint url
        $url = 'https://developer.co-opbank.co.ke:8243/Enquiry/AccountBalance/1.0.0';
        //generate api access token
        $token = self::generateSandBoxToken();
        $headers = array('Content-Type: application/json',"Authorization: Bearer {$token}");

        return Curl::to($url)
            ->withBearer($token)
            ->withContentType('application/json')
            ->withData([
                'AccountNumber' => $accountNumber,
                "MessageReference" => '40ca18c6765086089a1'
            ])
            ->asJson(true)
            ->returnResponseObject()
            ->post();
    }

    /**
     * @param string $accountNumber
     * @param int $numberOfTransactions
     * @return array|mixed|\stdClass
     */
    public static function getAccountTransactions($accountNumber = '36001873003', $numberOfTransactions = 3){
        $url = 'https://developer.co-opbank.co.ke:8243/Enquiry/AccountTransactions/1.0.0';
        //generate api access token
        $token = self::generateSandBoxToken();
        $headers = array('Content-Type: application/json',"Authorization: Bearer {$token}");

        $results = Curl::to($url)
            ->withBearer($token)
            ->withContentType('application/json')
            ->withData([
                'AccountNumber' => $accountNumber,
                "MessageReference" => '40ca18c6765086089a1',
                "NoOfTransactions" => $numberOfTransactions
            ])
            ->asJson(true)
            ->returnResponseObject()
            ->post();

        return $results;
    }

    /**
     * @param $startDate
     * @param $endDate
     * @param string $accountNumber
     * @return array|mixed|\stdClass
     */
    public static function getFullAccountStatement($startDate, $endDate, $accountNumber = '36001873003'){
        $url = 'https://developer.co-opbank.co.ke:8243/Enquiry/FullStatement/Account/1.0.0';
        //generate api access token
        $token = self::generateSandBoxToken();
        $headers = array('Content-Type: application/json',"Authorization: Bearer {$token}");

        $results = Curl::to($url)
            ->withBearer($token)
            ->withContentType('application/json')
            ->withData([
                'AccountNumber' => $accountNumber,
                "MessageReference" => '40ca18c6765086089a1',
                "StartDate" => Carbon::parse($startDate)->format('Y-m-d'),
                "EndDate" => Carbon::parse($endDate)->format('Y-m-d'),
            ])
            ->asJson(true)
            ->returnResponseObject()
            ->post();

        return $results;
    }

}
