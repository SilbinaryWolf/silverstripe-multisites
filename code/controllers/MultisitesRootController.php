<?php
/**
 * @package silverstripe-multisites
 */
class MultisitesRootController extends RootURLController {

	public function handleRequest(SS_HTTPRequest $request, DataModel $model = null) {
		self::$is_at_root = true;

		$this->setDataModel($model);
		$this->pushCurrent();
		$this->init();

		if(!DB::is_active() || !ClassInfo::hasTable('SiteTree')) {
			$this->response = new SS_HTTPResponse();
			$this->response->redirect(Director::absoluteBaseURL() . 'dev/build?returnURL=' . (isset($_GET['url']) ? urlencode($_GET['url']) : null));
			return $this->response;
		}

		$siteID = Multisites::inst()->getCurrentSiteId();
		if(!$siteID) {
			return $this->httpError(404, 'Site not found.');
		}

		$page = SiteTree::get()->filter(array(
			'ParentID'   => $siteID,
			'URLSegment' => static::get_homepage_link(),
		))->first();

		if(!$page) {
			return $this->httpError(404, 'Home not found.');
		}
			
		$request->setUrl(self::get_homepage_link() . '/');
		$request->match('$URLSegment//$Action', true);
		$controller = new ModelAsController();

		$result     = $controller->handleRequest($request, $model);
		
		$this->popCurrent();
		return $result;
	}
	
	/**
	 * The the (relative) homepage link.
	 * TODO: Should this deal with HomepageForDomain and Translatable the same way the core equivalent does?
	 * 
	 * @return string
	 */
	public static function get_homepage_link() {
		return Config::inst()->get(get_called_class(), 'default_homepage_link');
	}
	
	/**
	 * Returns TRUE if a request to a certain page should be redirected to the site root (i.e. if the page acts as the
	 * home page).
	 * 
	 * TODO: This function wouldn't be required if core called static::get_homepage_link() rather than self::get_homepage_link(). Raise a bug?
	 *
	 * @param SiteTree $page
	 * @return bool
	 */
	public static function should_be_on_root(SiteTree $page) {
		if(!self::$is_at_root && self::get_homepage_link() == trim($page->RelativeLink(true), '/')) {
			return !(
				class_exists('Translatable') && $page->hasExtension('Translatable') && $page->Locale && $page->Locale != Translatable::default_locale()
			);
		}
		return false;
	}

}
