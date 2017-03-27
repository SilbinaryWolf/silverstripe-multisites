<?php
/**
 * @package silverstripe-multisites
 */
class MultisitesCMSSiteTreeFilterTest extends CMSSiteTreeFilterTest {
	/** 
	 * Get parent class directory so it pulls the fixtures from that location instead.
	 */
	protected function getCurrentAbsolutePath() {
		$filename = self::$test_class_manifest->getItemPath(get_parent_class($this));
		if(!$filename) throw new LogicException("getItemPath returned null for " . get_parent_class($this));
		return dirname($filename);
	}

	public function testSearchFilterByTitle() {
		$page1 = $this->objFromFixture('Page', 'page1');
		$page2 = $this->objFromFixture('Page', 'page2');
	
		$f = new CMSSiteTreeFilter_Search(array('Title' => 'Page 1'));
		$results = $f->pagesIncluded();
	
		$this->assertTrue($f->isPageIncluded($page1));
		$this->assertFalse($f->isPageIncluded($page2));
		$this->assertEquals(1, count($results));
		$this->assertEquals(
			// NOTE: Change ParentID = 0, to ParentID = 1
			array('ID' => $page1->ID, 'ParentID' => 1),
			$results[0]
		);
	}
}
