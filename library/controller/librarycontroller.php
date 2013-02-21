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

use OCA\AppFramework\Controller\Controller;
use OCA\AppFramework\Db\DoesNotExistException;
use OCA\AppFramework\Http\RedirectResponse;
use \OCA\Library\Lib\ImageResponse;


use OCA\Library\Db\EBookMapper;
use OCA\Library\Db\EBook;


use OCA\Library\Db\AuthorMapper;
use OCA\Library\Db\Author;

use OCA\Library\Db\Item;
use OCA\Library\Lib\Cover;
use OCA\Library\Lib\HookHandler;

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



class LibraryController extends Controller {
	
	protected $libraryStorage;
	protected $authorMapper;
	protected $ebookMapper;
	/**
	 * @param Request $request: an instance of the request
	 * @param API $api: an api wrapper instance
	 * @param ItemMapper $itemMapper: an itemwrapper instance
	 */
	public function __construct($api, $request, $ebookMapper, $authorMapper, $libraryStorage){
		parent::__construct($api, $request);
		$this->ebookMapper = $ebookMapper;
		$this->authorMapper = $authorMapper;
		$this->libraryStorage = $libraryStorage;
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
	 * Redirects to the index page
	 */
	public function rescan(){
		
		$hh = new HookHandler($this->api,$this->ebookMapper);
		$hh->rescanImpl();
		return $this->redirectToIndex();
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

		$sortby = $this->params('sortby');
		$ebooks = $this->ebookMapper->findAllForUser($this->api->getUserId(),$sortby);
		
		$templateName = 'main';
		$paramsIn =  $this->getParams();
		$routeName = $paramsIn['_route'];
		// unset the _route param so that it is not re-sent
		unset($paramsIn['_route']);
		
		$params = array(
			'thisLink' => $this->api->linkToRoute($routeName, $paramsIn),
			'ebooks' => $ebooks,
			'userName' => $this->api->getDisplayName(),
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
		$paramsIn =  $this->getParams();
		$routeName = $paramsIn['_route'];
		// unset the _route param so that it is not re-sent
		unset($paramsIn['_route']);
		
		
		$templateName = 'opds_index';
		
		$mtime = $this->ebookMapper->latestMTime($this->api->getUserId());
		$currentTime = new \DateTime();
		if($mtime!== null) {
			$currentTime->setTimestamp($mtime);
		}
		
		$params = array(
			'thisLink' => $this->api->linkToRouteAbsolute($routeName, $paramsIn),
			'updateDate' => $currentTime->format(\DateTime::ATOM),		
			'userName' => $this->api->getDisplayName(),
			//'userMail' => 'TheSFReader@gmail.com',
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
		$paramsIn =  $this->getParams();
		$routeName = $paramsIn['_route'];
		// unset the _route param so that it is not re-sent
		unset($paramsIn['_route']);
	
		$templateName = 'opds_acquisition';
		
		$ebooks = $this->ebookMapper->findAllForUser($this->api->getUserId(),'newest');
		
		$currentTime = new \DateTime();
		$mtime = $this->ebookMapper->latestMTime($this->api->getUserId());
		if($mtime!== null) {
			$currentTime->setTimestamp($mtime);
		}
					
		//usort($ebooks,'OCA\Library\Controller\cmpNewest');
	
		$params = array(
				'thisLink' => $this->api->linkToRouteAbsolute($routeName, $paramsIn),
				'ebooks' => $ebooks,
				'updateDate' => $currentTime->format(\DateTime::ATOM),
				'userName' => $this->api->getDisplayName(),
				//'userMail' => 'TheSFReader@gmail.com',
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
	
		$id = $this->params('id');
		$ebook = $this->ebookMapper->find($id);
		
		$params =  $this->getParams();
		
		//$this->api->log(print_r($params,true));
		if(isset($params['update'])) {
			if(isset($params['title'])) {
				$ebook->Title($params['title']);
			}
			if(isset($params['publisher'])) {
				$ebook->Publisher($params['publisher']);
			}
			if(isset($params['description'])) {
				$ebook->Description($params['description']);
			}
			if(isset($params['language'])) {
				$ebook->Language($params['language']);
			}
			if(isset($params['isbn'])) {
				$ebook->ISBN($params['isbn']);
			}
			if(isset( $params['authorname'])) {
				$authornames = $params['authorname'];
				foreach ($authornames as $num => $authorname) {
					if($authorname){
						$as = $params['authoras'][$num];
						if(!$as) $as = $authorname;
						$authors[$as] = $authorname;
					}
				}
				$ebook->Authors($authors);
			}
			if(isset( $params['subjects'])) {
				$subjects = $params['subjects'];
				if(is_string($subjects)){
					if($subjects === ''){
						$subjects = array();
					}else{
						$subjects = explode(',',$subjects);
						$subjects = array_map('trim',$subjects);
					}
				}
				$ebook->Subjects($subjects);
			}
			
			$this->ebookMapper->update($ebook, $this->api->getUserId());
			$url = $this->api->linkToRoute('library_details', array('id' => $ebook->getId()));
			return new RedirectResponse($url);
		}
		// your own stuff
		$this->api->addStyle('style');
	
		
		
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
		$ebook = $this->ebookMapper->find($id);
		$cover = new Cover($this->api, $this->libraryStorage, $ebook);
		$coverImage = $cover->getCoverImage();
		$imageSizes = $ebook->ImageSizes();
		if(! isset($imageSizes['cover'])) {
			$imageSizes['cover'] = array('width' => $coverImage->width(), 'height' => $coverImage->height());
			$ebook->ImageSizes($imageSizes);
			$this->ebookMapper->update($ebook, $this->api->getUserId());
		}
		return new ImageResponse($coverImage);
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
		$ebook = $this->ebookMapper->find($id);
		$cover = new Cover($this->api, $this->libraryStorage, $ebook);
		
		$thumbnailImage = $cover->getThumbnailImage();
		$imageSizes = $ebook->ImageSizes();
		if(! isset($imageSizes['thumbnail'])) {
			$imageSizes['thumbnail'] = array('width' => $thumbnailImage->width(), 'height' => $thumbnailImage->height());
			$ebook->ImageSizes($imageSizes);
			$this->ebookMapper->update($ebook, $this->api->getUserId());
		}
		
		return new ImageResponse($thumbnailImage);
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
