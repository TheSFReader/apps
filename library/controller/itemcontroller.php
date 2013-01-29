<?php

/**
* ownCloud - Library plugin
*
* @author TheSFReader
* @copyright 2012 TheSFReader thesfreader@gmail.com 
*
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
* License as published by the Free Software Foundation; either
* version 3 of the License, or any later version.
*
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU AFFERO GENERAL PUBLIC LICENSE for more details.
*
* You should have received a copy of the GNU Affero General Public
* License along with this library.  If not, see <http://www.gnu.org/licenses/>.
*
*/

namespace OCA\Library\Controller;

use OCA\AppFramework\Controller\Controller as Controller;
use OCA\AppFramework\Db\DoesNotExistException as DoesNotExistException;
use OCA\AppFramework\Http\RedirectResponse as RedirectResponse;


use OCA\Library\Db\EBookMapper as EBookMapper;
use OCA\Library\Db\EBook as EBook;

use OCA\Library\Db\Item as Item;
use OCA\Library\Lib\Cover as Cover;

# ATOM catalog
const ATOM_CATALOG = 'application/atom+xml';
# Common catalog
const OPDS_MIME_CATALOG = 'application/atom+xml;profile=opds-catalog';
# Pure navigation feeds
const OPDS_MIME_NAV = 'application/atom+xml;profile=opds-catalog;kind=navigation';
# Feeds with acquisition links
const OPDS_MIME_ACQ = 'application/atom+xml;profile=opds-catalog;kind=acquisition';
# General format for a book details entry document
const OPDS_MIME_ENTRY = 'application/atom+xml;type=entry;profile=opds-catalog';
# OpenSearch
const OPENSEARCH_MIME = 'application/opensearchdescription+xml';

// EBook Comparison functions for sorting
function cmpNewest($a, $b)
{
	//throw new \Exception(var_dump($a));
	$al = $a->Mtime();
	$bl = $b->Mtime();
	if ($al === $bl) {
		return 0;
	}
	return ($al < $bl) ? +1 : -1;
}

function cmpAuthor($a, $b)
{
	$al = reset($a->Authors());
	$bl = reset($b->Authors());
	if ($al[0] === $bl[0]) {
		return 0;
	}
	return ($al[0] > $bl[0]) ? +1 : -1;
}

function cmpTitle($a, $b)
{
	//throw new \Exception(var_dump($a));
	$al = $a->Title();
	$bl = $b->Title();
	if ($al === $bl) {
		return 0;
	}
	return ($al > $bl) ? +1 : -1;
}

function cmpPublisher($a, $b)
{
	//throw new \Exception(var_dump($a));
	$al = $a->Publisher();
	$bl = $b->Publisher();
	if ($al === $bl) {
		return 0;
	}
	return ($al > $bl) ? +1 : -1;
}

class ItemController extends Controller {
	

	/**
	 * @param Request $request: an instance of the request
	 * @param API $api: an api wrapper instance
	 * @param ItemMapper $itemMapper: an itemwrapper instance
	 */
	public function __construct($api, $request, $itemMapper){
		parent::__construct($api, $request);
		$this->itemMapper = $itemMapper;
	}


	/**
	 * @CSRFExemption
	 * @IsAdminExemption
	 * @IsSubAdminExemption
	 *
	 * Redirects to the index page
	 */
	public function redirectToIndex(){
		$url = $this->api->linkToRoute('library_index');
		return new RedirectResponse($url);
	}


	/**
	 * @CSRFExemption
	 * @IsAdminExemption
	 * @IsSubAdminExemption
	 *
	 * @brief renders the index page
	 * @return an instance of a Response implementation
	 */
	public function index(){

		// your own stuff
		$this->api->addStyle('style');

		$ebookMapper = new EBookMapper($this->api);
		$ebooks = $ebookMapper->findAllForUser($this->api->getUserId());
		
		$sortby = $this->params('sortby');
		if($sortby !== null) {
			$functionName = 'OCA\\Library\\Controller\cmp' . ucfirst($sortby);
				
			if(function_exists($functionName))
				usort($ebooks,$functionName);
		}
		

		$templateName = 'main';
		$paramsIn =  $this->getAllParams();
		$routeName = $paramsIn['_route'];
		// unset the _route param so that it is not re-sent
		unset($paramsIn['_route']);
		
		$params = array(
			'thisLink' => $this->api->linkToRoute($routeName, $paramsIn),
			'ebooks' => $ebooks,
			'userName' => $this->api->getUserId(),
		);
		return $this->render($templateName, $params);
	}
	
	/**
	 * @CSRFExemption
	 * @IsAdminExemption
	 * @IsSubAdminExemption
	 *
	 * @brief renders the index page
	 * @return an instance of a Response implementation
	 */
	public function opds(){
		$paramsIn =  $this->getAllParams();
		$routeName = $paramsIn['_route'];
		// unset the _route param so that it is not re-sent
		unset($paramsIn['_route']);
		
		
		$templateName = 'opds_index';
		
		$ebookMapper = new EBookMapper($this->api);
		$mtime = $ebookMapper->latestMTime($this->api->getUserId());
		$currentTime = new \DateTime();
		if($mtime!== null) {
			$currentTime->setTimestamp($mtime);
		}
		
		$params = array(
			'thisLink' => $this->api->linkToRouteAbsolute($routeName, $paramsIn),
			'opdsLink' => $this->api->linkToRouteAbsolute('library_opds'),
			'indexLink' => $this->api->linkToRouteAbsolute('library_index'),
			'newestLink' => $this->api->linkToRouteAbsolute('library_opds_new'),
				//FIXME
			'updateDate' => $currentTime->format(\DateTime::ATOM),		
			'userName' => $this->api->getUserId(),
			'userMail' => 'TheSFReader@gmail.com',
			'libraryName' => $this->api->getUserId() .'\'s Library',
		);
		$headers = array();
		$headers[]= 'Content-Type: '. OPDS_MIME_CATALOG;
		return $this->render($templateName, $params,false, $headers);
	}
	
	/**
	 * @CSRFExemption
	 * @IsAdminExemption
	 * @IsSubAdminExemption
	 *
	 * @brief renders the index page
	 * @return an instance of a Response implementation
	 */
	public function opds_new(){
		$paramsIn =  $this->getAllParams();
		$routeName = $paramsIn['_route'];
		// unset the _route param so that it is not re-sent
		unset($paramsIn['_route']);
	
		$templateName = 'opds_acquisition';
		
		$ebookMapper = new EBookMapper($this->api);
		$ebooks = $ebookMapper->findAllForUser($this->api->getUserId());
		
		$currentTime = new \DateTime();
		$lastMTime = null;
		foreach($ebooks as $ebook) {
			// Extract the time (for now)
			$thismtime = $ebook->MTime(); 
			if($lastMTime == null || $thismtime > $lastMTime)
				$lastMTime = $thismtime;
		}
		if($lastMTime!== null) {
			$currentTime->setTimestamp($lastMTime);
		}
			
		usort($ebooks,'OCA\Library\Controller\cmpNewest');
	
		$params = array(
				'thisLink' => $this->api->linkToRouteAbsolute($routeName, $paramsIn),
				'opdsLink' => $this->api->linkToRouteAbsolute('library_opds'),
				'indexLink' => $this->api->linkToRouteAbsolute('library_index'),
				'newestLink' => $this->api->linkToRouteAbsolute('library_opds_new'),
				'ebooks' => $ebooks,
				'updateDate' => $currentTime->format(\DateTime::ATOM),
				'userName' => $this->api->getUserId(),
				'userMail' => 'TheSFReader@gmail.com',
				'libraryName' => $this->api->getUserId() .'\'s Library',
		);
		$headers = array();
		$headers[]= 'Content-Type: '. OPDS_MIME_CATALOG;
		return $this->render($templateName, $params,false, $headers);
	}


	/**
	 * @CSRFExemption
	 * @IsAdminExemption
	 * @IsSubAdminExemption
	 *
	 * @brief renders the index page
	 * @return an instance of a Response implementation
	 */
	public function details(){
	
		// your own stuff
		$this->api->addStyle('style');
	
		$id = $this->params('id');
		$ebook = new EBook($this->api, $id);
	
		$templateName = 'details';
		$params = array(
				'ebook' => $ebook,
				'indexLink' => $this->api->linkToRoute('library_index'),
		);
		return $this->render($templateName, $params);
	}
	

	/**
	 * @CSRFExemption
	 * @IsAdminExemption
	 * @IsSubAdminExemption
	 *
	 * @brief renders the index page
	 * @param array $urlParams: an array with the values, which were matched in
	 *                          the routes file
	 * @return an instance of a Response implementation
	 */
	public function cover(){
	
		$id = $this->params('id');
		$path = $this->api->getPath($id);
		return Cover::getCover($this->api, $path);
	}
	
	/**
	 * @CSRFExemption
	 * @IsAdminExemption
	 * @IsSubAdminExemption
	 *
	 * @brief renders the index page
	 * @param array $urlParams: an array with the values, which were matched in
	 *                          the routes file
	 * @return an instance of a Response implementation
	 */
	public function thumbnail(){
		$id = $this->params('id');
		$path = $this->api->getPath($id);
		return Cover::getThumbnail($this->api, $path);
	}
	
	/**
	 * @Ajax
	 *
	 * @brief sets a global system value
	 * @param array $urlParams: an array with the values, which were matched in 
	 *                          the routes file
	 */
	public function setSystemValue(){
		$value = $this->params('somesetting');
		$this->api->setSystemValue('somesetting', $value);

		$params = array(
			'somesetting' => $value
		);

		return $this->renderJSON($params);
	}
}
