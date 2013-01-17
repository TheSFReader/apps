<?php

/**
* ownCloud - App Template Example
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

namespace OCA\AppLibrary;

/**
 * Declare your classes and their include path so that they'll be automatically
 * loaded once you instantiate them
 */

\OC::$CLASSPATH['OCA\AppLibrary\DIContainer'] = 'apps/library/appinfo/dicontainer.php';

\OC::$CLASSPATH['OCA\AppLibrary\ItemMapper'] = 'apps/library/database/item.mapper.php';
\OC::$CLASSPATH['OCA\AppLibrary\Item'] = 'apps/library/database/item.php';

\OC::$CLASSPATH['OCA\AppLibrary\ItemController'] = 'apps/library/controllers/item.controller.php';
\OC::$CLASSPATH['OCA\AppLibrary\SettingsController'] = 'apps/library/controllers/settings.controller.php';


/**
 * My own classes
 */

\OC::$CLASSPATH['EPub'] = 'apps/library/3rdparty/php-epub-meta-master/epub.php';
\OC::$CLASSPATH['OCA\AppLibrary\EBook'] = 'apps/library/database/ebook.php';
\OC::$CLASSPATH['OCA\AppLibrary\Cover'] = 'apps/library/lib/cover.php';








