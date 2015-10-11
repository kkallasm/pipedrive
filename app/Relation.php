<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Relation extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'relations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['relation_id', 'parent_org_id', 'org_id', 'type'];


    public static function getRelationsByOrgId($org_id)
    {    
        $target_org = Organization::find($org_id);
        if($target_org == null)
            return array('success' => false, 'error' => 'organization not found by ID '.$org_id);

        $result['success'] = true;
        $data = array();
    	$daughters = Relation::where('parent_org_id',$org_id)->get();
        $parent    = Relation::where('org_id',$org_id)->get()->first();

        $parent_org = ($parent == null)?$target_org:Organization::find($parent->parent_org_id);

        //is parent organization
        if($parent != null)
        {
            $data[] = array(
                    'id' => $parent->relation_id, 
                    'type' => $parent->type, 
                    'calculated_type' => 'parent', 
                    'parent_org' => array('id' => $parent_org->id, 'name' => $parent_org->name),
                    'daughter_org' => array('id' => $target_org->id, 'name' => $target_org->name));
        }

        if($daughters && count($daughters))
        {
            foreach ($daughters as $key => $value) {
                $org = Organization::find($value->org_id);
                $data[] = array(
                    'id' => $value->relation_id, 
                    'type' => $value->type, 
                    'calculated_type' => 'daughter', 
                    'parent_org' => array('id' => $parent_org->id, 'name' => $parent_org->name),
                    'daughter_org' => array('id' => $org->id, 'name' => $org->name));
            }
        }

        $result['data'] = $data;

    	return $result;
    }
}
