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


\OCP\App::registerAdmin('library', 'admin/settings');

\OCP\App::addNavigationEntry( array(
	
	// the string under which your app will be referenced
	// in owncloud, for instance: \OC_App::getAppPath('APP_ID')
	'id' => 'library',

	// sorting weight for the navigation. The higher the number, the higher
	// will it be listed in the navigation
	'order' => 75,
	
	// the route that will be shown on startup
	'href' => \OC_Helper::linkToRoute('library_index'),
	
	// the icon that will be shown in the navigation
	'icon' => \OCP\Util::imagePath('library', 'example.png' ),
	
	// the title of your application. This will be used in the
	// navigation or on the settings page of your app
	'name' => \OC_L10N::get('library')->t('Library') 
	
));


\OCP\Util::connectHook(\OC\Files\Filesystem::CLASSNAME, \OC\Files\Filesystem::signal_post_write, 'OCA\Library\Lib\HookHandler', 'writeFile');
\OCP\Util::connectHook(\OC\Files\Filesystem::CLASSNAME, \OC\Files\Filesystem::signal_delete, 'OCA\Library\Lib\HookHandler', 'removeFile');
\OCP\Util::connectHook(\OC\Files\Filesystem::CLASSNAME, \OC\Files\Filesystem::signal_post_rename, "OCA\Library\Lib\HookHandler", "renameFile");
\OCP\Util::connectHook('OC_User', 'post_deleteUser', "OCA\Library\Lib\HookHandler", "removeUser");

