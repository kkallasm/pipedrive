<?php

namespace App;
use Illuminate\Http\ResponseTrait;
use App\Organization;

class PipedriveAPI
{
	public static $ch_org;
	public static $ch_rel;

	protected static function getResultId($res)
	{
		if(isset($res['success']) && $res['success'] == true && isset($res['data']))
		{
			return intval($res['data']['id']);
		}
		else return null;
	}

    public static function createOrganization($name)
	{
		$data = array('name' => $name);                                                                   
		$data_string = json_encode($data);                                                                                   

		if(!self::$ch_org)
			self::$ch_org = curl_init('https://api.pipedrive.com/v1/organizations?api_token='.env('PIPEDRIVE_API_TOKEN')); 
                                                                     
		curl_setopt(self::$ch_org, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt(self::$ch_org, CURLOPT_POSTFIELDS, $data_string);                                                                  
		curl_setopt(self::$ch_org, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt(self::$ch_org, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',                                                                                
			'Content-Length: ' . strlen($data_string))                                                                       
		);                                                                                                                   
			                                                                                                                     
		$response = json_decode(curl_exec(self::$ch_org),true);

	    //curl_close(self::$ch);
	    return self::getResultId($response);
	}

	public static function createRelation($parent_org_id,$org_id)
	{
		$data = array('rel_owner_org_id' => $parent_org_id, 'rel_linked_org_id' => $org_id, 'type' => 'parent');                                                                   
		$data_string = json_encode($data);                                                                                   
		
		if(!self::$ch_rel)
			self::$ch_rel = curl_init('https://api.pipedrive.com/v1/organizationRelationships?api_token='.env('PIPEDRIVE_API_TOKEN')); 
                                                                     
		curl_setopt(self::$ch_rel, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt(self::$ch_rel, CURLOPT_POSTFIELDS, $data_string);                                                                  
		curl_setopt(self::$ch_rel, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt(self::$ch_rel, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',                                                                                
			'Content-Length: ' . strlen($data_string))                                                                       
		);                                                                                                                   
			                                                                                                                     
		$response = json_decode(curl_exec(self::$ch_rel),true);

	    //curl_close(self::$ch);
	    return self::getResultId($response);
	}
}
