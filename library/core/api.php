<?php

/**
 * ownCloud - App Framework
 *
 * @author Bernhard Posselt
 * @copyright 2012 Bernhard Posselt nukeawhale@gmail.com
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


namespace OCA\Library\Core;

use \OCA\AppFramework\Core\API as ParentAPI;

/**
 * This is used to wrap the owncloud static api calls into an object to make the
 * code better abstractable for use in the dependency injection container
 *
 * Should you find yourself in need for more methods, simply inherit from this
 * class and add your methods
 */
class API extends ParentAPI {

	

	
	
	
	/**
	 * Returns the Absolute URL for a route
	 * @return the url
	 */
	public function linkToRouteAbsolute($routeName, $params = array()){
		return \OC_Helper::makeURLAbsolute (\OC_Helper::linkToRoute($routeName, $params));
	}
	
	/**
	 * @brief Makes an $url absolute
	 * @param string $url the url
	 * @return string the absolute url
	 *
	 * Returns a absolute url to the given url.
	 */
	public function makeURLAbsolute($url){
		return \OC_Helper::makeURLAbsolute ($url);
	}
	
	/**
	 * Makes an URL absolute
	 * @param string $url the url
	 * @return string the absolute url
	 */
	public function getAbsoluteURL($url){
		return \OC_Helper::makeURLAbsolute($url);
	}


	/**
	 * links to a file
	 * @param string $file the name of the file
	 * @param string $appName the name of the app, defaults to the current one
	 * @deprecated replaced with linkToRoute()
	 * @return string the url
	 */
	public function linkToAbsolute($file, $appName=null, $params = array()){
		if($appName === null){
			$appName = $this->appName;
		}
		return \OC_Helper::linkToAbsolute($appName, $file, $params);
	}


	


	/**
	 * Returns a local file path
	 * @return the path
	 */
	public function getLocalFile($path){
		return \OC\Files\Filesystem::getLocalFile($path);
	}
	
	/**
	 * get the filesystem info from the cache
	 * @param string path
	 * @param string root (optional)
	 * @return array
	 *
	 * returns an associative array with the following keys:
	 * - size
	 * - mtime
	 * - ctime
	 * - mimetype
	 * - encrypted
	 * - versioned
	 */
	public function getFilesystemInfo($path){
		return  \OC\Files\Filesystem::getFileInfo($path);
	}





	/**
	 * turns an owncloud path into a path on the filesystem
	 * @param string path the path to the file on the oc filesystem
	 * @return string the filepath in the filesystem
	 */
	public function getLocalFilePath($path){
		$view = new \OC\Files\View('');	
		return $view->getLocalFile($path);
	}

	
	/**
	 * @brief Creates path to an image
	 * @param string $app app
	 * @param string $image image name
	 * @returns string the url
	 *
	 * Returns the path to the image.
	 */
	public function getImagePath($appliName, $imagePathInAppli){
		return \OCP\Util::imagePath($appliName, $imagePathInAppli);
	}
	
	/**
	 * search for files by mimetype
	 *
	 * @param string $mimetype
	 * @return array
	 */
	public function searchByMime($mimetype) {
		return \OC\Files\Filesystem::searchByMime($mimetype);
	}
	
	/**
	 * Get the path of a file by id
	 *
	 * Note that the resulting path is not guarantied to be unique for the id, multiple paths can point to the same file
	 *
	 * @param int $id
	 * @return string
	 */
	public function getPath($id) {
		return \OC\Files\Filesystem::getPath($id);
	}
	/*
	 * create an image
	* @param $imageref The path to a local file, a base64 encoded string or a resource created by an imagecreate* function.
	*/
	public function createImage($imageref) {
		return new \OC_Image($imageref);
	}
	
	
	/**
	 * @brief get the user display name of the user currently logged in.
	 * @return string display name
	 */
	public function getDisplayName($user=null) {
		return \OC_USER::getDisplayName($user);
	}
	
	
}