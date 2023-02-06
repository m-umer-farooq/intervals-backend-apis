<?php

use Carbon\Carbon;
use App\Models\Log;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;



/**
 * log_data function
 * [] as param
 * @return boolean
 */
if (!function_exists('log_data')) {

    function log_data($data)
    {
        $res = Log::create($data);
        if ($res) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}

/**
 * Write code on Method
 *
 * @return response()
 */
if (!function_exists('convertYmdToMdy')) {
    function convertYmdToMdy($date)
    {
        return Carbon::createFromFormat('Y-m-d', $date)->format('m-d-Y');
    }
}


/**
 * Write code on Method
 *
 * @return response()
 */
if (!function_exists('toUTF8')) {
    function toUTF8($string)
    {
        $entities = array(
            'À' => '&Agrave;',
            'à' => '&agrave;',
            'Á' => '&Aacute;',
            'á' => '&aacute;',
            'Â' => '&Acirc;',
            'â' => '&acirc;',
            'Ã' => '&Atilde;',
            'ã' => '&atilde;',
            'Ä' => '&Auml;',
            'ä' => '&auml;',
            'Å' => '&Aring;',
            'å' => '&aring;',
            'Æ' => '&AElig;',
            'æ' => '&aelig;',
            'Ç' => '&Ccedil;',
            'ç' => '&ccedil;',
            '?' => '&ETH;',
            '?' => '&eth;',
            'È' => '&Egrave;',
            'è' => '&egrave;',
            'É' => '&Eacute;',
            'é' => '&eacute;',
            'Ê' => '&Ecirc;',
            'ê' => '&ecirc;',
            'Ë' => '&Euml;',
            'ë' => '&euml;',
            'Ì' => '&Igrave;',
            'ì' => '&igrave;',
            'Í' => '&Iacute;',
            'í' => '&iacute;',
            'Î' => '&Icirc;',
            'î' => '&icirc;',
            'Ï' => '&Iuml;',
            'ï' => '&iuml;',
            'Ñ' => '&Ntilde;',
            'ñ' => '&ntilde;',
            'Ò' => '&Ograve;',
            'ò' => '&ograve;',
            'Ó' => '&Oacute;',
            'ó' => '&oacute;',
            'Ô' => '&Ocirc;',
            'ô' => '&ocirc;',
            'Õ' => '&Otilde;',
            'õ' => '&otilde;',
            'Ö' => '&Ouml;',
            'ö' => '&ouml;',
            'Ø' => '&Oslash;',
            'ø' => '&oslash;',
            'Œ' => '&OElig;',
            'œ' => '&oelig;',
            'ß' => '&szlig;',
            '?' => '&THORN;',
            '?' => '&thorn;',
            'Ù' => '&Ugrave;',
            'ù' => '&ugrave;',
            'Ú' => '&Uacute;',
            'ú' => '&uacute;',
            'Û' => '&Ucirc;',
            'û' => '&ucirc;',
            'Ü' => '&Uuml;',
            'ü' => '&uuml;',
            '?' => '&Yacute;',
            '?' => '&yacute;',
            'Ÿ' => '&Yuml;',
            'ÿ' => '&yuml;'
        );

        foreach ($entities as $key => $value) {
            $ent[] = $key;
            $html_ent[] = $value;
        }

        $new_string = str_replace($html_ent, $ent, $string);

        return $new_string;
    }
}

/**
 * Write code on Method
 *
 * @return response()
 */
if (!function_exists('convertMdyToYmd')) {
    function convertMdyToYmd($date)
    {
        return Carbon::createFromFormat('m-d-Y', $date)->format('Y-m-d');
    }
}

if (!function_exists('testHelper')) {
    function testHelper()
    {
        return 'Hello from test helper';
    }
}

/**
 * createGuzzleRequest
 * param: api_key (for basic headers authorization) , request_type (GET, POST, PUT, UPDATE, DELETE), url (Endpoint URL)
 * returns: api response
 * @return response()
 */

if (!function_exists('createGuzzleRequest')) {
    function createGuzzleRequest($api_key, $request_type, $url)
    {
        $client = new Client();

        $headers = [
            'Accept'        => 'application/json',
            'Authorization' => 'Basic ' . base64_encode($api_key . ':X')
        ];

        $response = $client->request($request_type, $url, ['headers' => $headers]);
        return $response;
    }
}


if (!function_exists('makeIntervalsApiCall')) {

    function makeIntervalsApiCall($api_key, $request_type, $url, $data = null)
    {
        $base_url = 'https://api.myintervals.com/';

        $url = $base_url . $url;

        $headers = [
            'Authorization' => 'Basic ' . base64_encode($api_key . ':X'),
            'Accept'        => 'application/json',
            'Cache-Control' => 'no-cache'
        ];

        if ($request_type === 'POST') {

            $response = Http::withHeaders($headers)->post($url, $data);
        } elseif ($request_type === 'GET') {

            $response = Http::withHeaders($headers)->get($url, $data);
        } elseif ($request_type === 'PUT') {
            $response = Http::withHeaders($headers)->put($url, $data);
        } elseif ($request_type === 'DELETE') {
            $response = Http::withHeaders($headers)->delete($url, $data);
        } else {
        }

        // Determine if the status code is >= 400...
        if ($response->failed()) {
            return $response->object();
            //return response()->json(['message' => 'No Record Found'], 400);
        }

        // Determine if the response has a 400 level status code...
        if ($response->clientError()) {
            return $response->object();
        }

        // Determine if the response has a 500 level status code...
        if ($response->serverError()) {
            return $response->object();
        }

        if ($response->successful()) {
            /* print_r($response->getBody()->getContents());
            exit; */
            return $response->object();
        }
    }
}
