<?php

class MultisitesSiteTreeBacklinksTest extends SiteTreeBacklinksTest {
	/** 
	 * Get parent class directory so it pulls the fixtures from that location instead.
	 */
	protected function getCurrentAbsolutePath() 
	{
		$filename = self::$test_class_manifest->getItemPath(get_parent_class($this));
		if(!$filename) throw new LogicException("getItemPath returned null for " . get_parent_class($this));
		return dirname($filename);
	}

	public function setUp() {
		parent::setUp();
		
		//$page1 = $this->objFromFixture('page1');
		//var_dump($page1->ID);
		//$page3 = $this->objFromFixture('page3');
	}
}