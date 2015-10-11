<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use GuzzleHttp\Client;
use App\PipedriveAPI;
use App\Organization;
use App\Relation;


class RelationController extends Controller
{
	public $names = array();

    public function index()
	{
		
	}

	protected function createRelations($res, $parent_org_id, &$response)
	{
		if(isset($res['org_name']) && $res['org_name'])
		{
			//check for duplicates
			if(in_array($res['org_name'],$this->names))
			{
				$response[] = array('success' => false, 'error' => "Duplicate organization name - ".$res['org_name']);
				return;
			}

			$org_id = PipedriveAPI::createOrganization($res['org_name']);

			if(!$parent_org_id)
				$parent_org_id = $org_id;

			if($org_id)
			{	
				//add new organization
				$org = new Organization();
	  		    $org->id = $org_id;
	  		    $org->name = $res['org_name'];
	  		    $org->save();

	  		    $this->names[] = $res['org_name'];

	  		    if($parent_org_id && $parent_org_id != $org_id)
	  		    {	
		  		    $relation_id = PipedriveAPI::createRelation($parent_org_id,$org_id);

		       		//add new relation
		       		if($relation_id)
		       		{
		       			$relation = new Relation();
		       			$relation->relation_id = $relation_id;
		       			$relation->parent_org_id = $parent_org_id;
		       			$relation->org_id = $org_id;
		       			$relation->type = 'parent';
		       			$relation->save();

		       			$response[] = array('success' => true, 'parent_org_id' => $parent_org_id, 'org_id' => $org_id, 'org_name' => $res['org_name'], 'ralation_id' => $relation_id);
		       		}

	  			}

				if(isset($res['daughters']) && $res['daughters'] && is_array($res['daughters']))
				{
					$parent_org_id = $org_id;
					foreach($res['daughters'] as $daughter)
					{
						$this->createRelations($daughter,$parent_org_id,$response);
					}
				}
			}
		}
	}

	/**
	 *	inserts new data
	 */
	public function create(Request $request)
	{
		if($request->isJson())
		{
			$res = $request->all();
			$response = array();
			$this->createRelations($res, null, $response);
			return $response;				
		}
		else
		{
			return response()->json(['success' => false, 'error' => 'Expected JSON request']);	
		}
	}

    /**
	 *	delets all data from database
	 */
	public function delete()
	{
		Relation::truncate();
		Organization::truncate();

		return response()->json(['success' => true]);
	}

	/**
	 *	get relations from database
	 */
	public function show(Request $request)
	{
		if($request->isJson())
		{
			$res = $request->all();
			if(isset($res['org_id']) && intval($res['org_id']))
			{
				$relations = Relation::getRelationsByOrgId($res['org_id']);
				return response()->json($relations);
			}
			else return response()->json(['success' => false, 'error' => 'Invalid org_id parameter']);
		}
		else return response()->json(['success' => false, 'error' => 'Expected JSON request']);
	}
}
