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

namespace OCA\Library;

use \OCA\AppFramework\App as App;

use \OCA\Library\DependencyInjection\DIContainer as DIContainer;


/*************************
 * Define your routes here
 ************************/

/**
 * Normal Routes
 */
$this->create('library_index', '/')->action(
	function($params){
		App::main('ItemController', 'index', $params, new DIContainer());
	}
);

/*
$this->create('library_index2', '/{sortby}')->defaults(array('sortby' => 'newest'))->action(
		function($params){
			\OC_Log::write("Library",var_export($params,true),4);
			App::main('ItemController', 'index', $params, new DIContainer());
		}
);*/


$this->create('library_opds', '/opds')->action(
		function($params){
			App::main('ItemController', 'opds', $params, new DIContainer());
		}
);

$this->create('library_opds_all', '/opds/all')->action(
		function($params){
			App::main('ItemController', 'opds_all', $params, new DIContainer());
		}
);

$this->create('library_opds_new', '/opds/new')->action(
		function($params){
			App::main('ItemController', 'opds_new', $params, new DIContainer());
		}
);

$this->create('library_index_sort', '/sortby/{sortby}')->action(
		function($params){
			
			App::main('ItemController', 'index', $params, new DIContainer());
		}
);

$this->create('library_index_sort_paginated', '/sortby/{sortby}/{page}')->action(
		function($params){
			App::main('ItemController', 'index', $params, new DIContainer());
		}
);

$this->create('library_details', '/ebook/{id}')->action(
		function($params){
			App::main('ItemController', 'details', $params, new DIContainer());
		}
);

$this->create('library_cover', '/cover/{id}')->action(
		function($params){
			App::main('ItemController', 'cover', $params, new DIContainer());
		}
);



$this->create('library_thumbnail', '/thumbnail/{id}')->action(
		function($params){
			App::main('ItemController', 'thumbnail', $params, new DIContainer());
		}
);


/**
 * predefined routes
 */

$this->create('library_index_param', '/test/{test}')->action(
	function($params){
		App::main('ItemController', 'index', $params, new DIContainer());
	}
);

$this->create('library_index_redirect', '/redirect')->action(
	function($params){
		App::main('ItemController', 'redirectToIndex', $params, new DIContainer());
	}
);

/**
 * Ajax Routes
 */
$this->create('library_ajax_setsystemvalue', '/setsystemvalue')->post()->action(
	function($params){
		App::main('ItemController', 'setSystemValue', $params, new DIContainer());
	}
);
