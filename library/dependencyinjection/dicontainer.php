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

namespace OCA\Library\DependencyInjection;

use OCA\AppFramework\DependencyInjection\DIContainer as BaseContainer;

use OCA\Library\Controller\LibraryController;
use OCA\Library\Controller\SettingsController as SettingsController;
use OCA\Library\Db\ItemMapper as ItemMapper;
use OCA\Library\Db\EBookMapper;
use OCA\Library\Db\AuthorMapper;

use OCA\Library\Core\API;


class DIContainer extends BaseContainer {


	/**
	 * Define your dependencies in here
	 */
	public function __construct(){
		// tell parent container about the app name
		parent::__construct('library');

		
		// Replace the GLobal API by our own
		$this['API'] = $this->share(function($c){
			return new API($c['AppName']);
		});
		
			// enables the linkToRoute function as url() function in twig
			$this['TwigImagePath'] = $this->share(function($c){
				$api = $c['API'];
				return new \Twig_SimpleFunction('image_path', function () use ($api) {
					return call_user_func_array(array($api, 'getImagePath'), func_get_args());
				});
			});
					
			$this['TwigLoader'] = $this->share(function($c){
				return new \Twig_Loader_Filesystem($c['TwigTemplateDirectory']);
			});
		
			$this['Twig'] = $this->share(function($c){
				$loader = $c['TwigLoader'];
				if($c['TwigTemplateCacheDirectory'] !== null){
					$twig = new \Twig_Environment($loader, array(
							'cache' => $c['TwigTemplateCacheDirectory'],
							'autoescape' => true
					));
				} else {
					$twig = new \Twig_Environment($loader, array(
							'autoescape' => true
					));
				}
				$api = $c['API'];
				$twig->addGlobal('api',$api);
				$twig->addFunction($c['TwigL10N']);
				$twig->addFunction($c['TwigLinkToRoute']);
				$twig->addFunction($c['TwigLinkToAbsoluteRoute']);
				$twig->addFunction($c['TwigImagePath']);
				return $twig;
			});
		
		/** 
		 * CONTROLLERS
		 */
		$this['LibraryController'] = $this->share(function($c){
			return new LibraryController($c['API'], $c['Request'], $c['EBookMapper'], $c['AuthorMapper'], $c['LibraryStorage']);
		});
		
		

		$this['SettingsController'] = $this->share(function($c){
			return new SettingsController($c['API'], $c['Request']);
		});


		/**
		* TWIG ?
		 */
			$this['TwigTemplateDirectory'] = __DIR__ . '/../templates';
		
		/**
		 * MAPPERS
		 */
		$this['EBookMapper'] = $this->share(function($c){
			return new EBookMapper($c['API'], $c['AuthorMapper']);
		});
		
		$this['AuthorMapper'] = $this->share(function($c){
			return new AuthorMapper($c['API']);
		});
		
		$this['LibraryStorage'] = $this->share(function($c){
			return \OCP\Files::getStorage('library');
		});


	}
}

