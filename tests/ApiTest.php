<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Organization;
use App\Relation;

class ApiTest extends TestCase
{
	public function generateData()
	{
		$this->get('/generateData');
	}

    public function testDeleteEndpoint()
    {
        $this->delete('/relations')
             ->seeJson([
                 'success' => true,
             ]);

		$this->assertEquals(0, Organization::get()->count());
		$this->assertEquals(0, Relation::get()->count());
    }

    public function testGetRelationsEndpoint()
    {
    	$this->generateData();

    	$org_id = Organization::get()->first()->id;
    	$content = Relation::getRelationsByOrgId($org_id);
    	$this->get('/relations?org_id='.$org_id)
             ->seeJson($content);

        //catch error
    	$this->get('/relations?org_id=')
             ->seeJson([
                 'success' => false,
                 'error' => 'Invalid org_id parameter'
             ]);
    }

	public function testCreateRelationsEndpoint()
    {
    	$this->delete('/relations');

        $data = array("org_name" => "Paradise Island",
    				  "daughters" => array(0 => array("org_name" => "Banana tree", 
    				  "daughters" => array(0 => array("org_name" => "Yellow Banana"), 1 => array("org_name" => "Red Banana"), 2 => array("org_name" => "Green Banana"))
    				  )));

    	$this->post('/relations', $data)
    		 ->seeJson(['success' => true]);

    	$this->assertEquals(5, Organization::get()->count());
		$this->assertEquals(4, Relation::get()->count());

		$this->post('/relations')
    		 ->seeJson(['success' => false, 'error' => 'No request parameters']); 	

    	$this->delete('/relations');

    	$data = array("org_name" => "Paradise Island",
    				  "daughters" => array(0 => array("org_name" => "Banana tree", 
    				  "daughters" => array(0 => array("org_name" => "Yellow Banana"), 1 => array("org_name" => "Yellow Banana"), 2 => array("org_name" => "Green Banana"))
    				  )));

    	$this->post('/relations', $data)
    		 ->seeJson(['success' => false])
    		 ->see('Duplicate organization name');

    	$this->assertEquals(4, Organization::get()->count());
		$this->assertEquals(3, Relation::get()->count());
    }   


}
