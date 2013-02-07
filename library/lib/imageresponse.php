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


namespace OCA\Library\Lib;
use OCA\AppFramework\Http\Response;

/**
 * Returns the image stream.
 */
class ImageResponse extends Response {
	
	private $image;

	/**
	 * Creates a response that prompts the user to download the file
	 * @param image $Image hte image to answer
	 */
	public function __construct($image){
		parent::__construct();
		$this->image = $image;
	}
	
	function render() {
		parent::render();
		if($this->image !== null)
			$this->image->show();
	}


}
