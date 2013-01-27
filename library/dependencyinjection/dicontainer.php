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

use OCA\Library\Controller\ItemController as ItemController;
use OCA\Library\Controller\SettingsController as SettingsController;
use OCA\Library\Db\ItemMapper as ItemMapper;
use OCA\Library\Db\EBookMapper as EBookMapper;


class DIContainer extends BaseContainer {


	/**
	 * Define your dependencies in here
	 */
	public function __construct(){
		// tell parent container about the app name
		parent::__construct('library');

		/** 
		 * CONTROLLERS
		 */
		$this['ItemController'] = $this->share(function($c){
			return new ItemController($c['API'], $c['Request'], $c['EBookMapper']);
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
			return new EBookMapper($c['API']);
		});


	}
}

