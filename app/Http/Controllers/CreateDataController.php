<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Organization;

class CreateDataController extends Controller
{	
    public function createRelations()
    {
    	$data = array("org_name" => "Paradise Island",
    				  "daughters" => array(0 => array("org_name" => "Banana tree", 
    				  "daughters" => array(0 => array("org_name" => "Yellow Banana"), 1 => array("org_name" => "Red Banana"), 2 => array("org_name" => "Green Banana"))
    				  )));
 
    	$data = json_encode($data);

    	$url = env('BASE_URL').'/relations';
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	    curl_setopt($ch, CURLOPT_PORT, 80);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	        'Content-Type: application/json',
	        'Content-Length: ' . strlen($data))
	    );
	    $response = curl_exec($ch);
	    curl_close($ch);
	    return $response;
	}

}
