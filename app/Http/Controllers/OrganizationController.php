<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Organization;

class OrganizationController extends Controller
{	

    public function showResults()
    {
    	$orgs = Organization::get();

    	if($orgs)
    	{
    		foreach ($orgs as $org) {
    			$org->getRelatedParentOrganizations();
    		}
    		
    	}
    }

    public function generateData()
    {

	    	$msg = '{
"org_name": "Paradise Island", "daughters": [
{
"org_name": "Banana tree",
"daughters": [
{"org_name": "Yellow Banana"}, {"org_name": "Yellow Banana"}, {"org_name": "Green Banana"}
] }
] }';
			//$msg = json_encode(array('org_id' => 147));

	        $url = 'http://pipedrive.app:8000/relations';
	        $ch = curl_init($url);
	        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
	        curl_setopt($ch, CURLOPT_PORT, 80);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $msg);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	            'Content-Type: application/json',
	            'Content-Length: ' . strlen($msg))
	        );
	        $response = curl_exec($ch);
	        
	        curl_close($ch);
	        print "<pre>";
	        return $response;
	}

}
