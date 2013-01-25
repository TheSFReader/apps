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

namespace OCA\Library\Db;

use \OCA\AppFramework\Core\API as API;
use \OCA\AppFramework\Db\Mapper as Mapper;
use \OCA\AppFramework\Db\DoesNotExistException as DoesNotExistException;


class EBookMapper extends Mapper {


	private $tableName;

	/**
	 * @param API $api: Instance of the API abstraction layer
	 */
	public function __construct($api){
		parent::__construct($api);
		$this->tableName = '*PREFIX*library_ebooks';
	}


	/**
	 * Finds an item by id
	 * @throws DoesNotExistException: if the item does not exist
	 * @return the item
	 */
	public function find($id){
		$row = $this->findQuery($this->tableName, $id);
		return new EBook($api, $row);
		
	}


	/**
	 * Finds an item by user id
	 * @param string $userId: the id of the user that we want to find
	 * @throws DoesNotExistException: if the item does not exist
	 * @return the item
	 */
	public function findByUserId($userId){
		$sql = 'SELECT * FROM ' . $this->tableName . ' WHERE user = ?';
		$params = array($userId);

		$result = $this->execute($sql, $params)->fetchRow();
		if($result){
			return new EBook($api, $result);
		} else {
			throw new DoesNotExistException('EBook with user id ' . $userId . ' does not exist!');
		}
	}


	/**
	 * Finds all Items
	 * @return array containing all items
	 */
	public function findAll(){
		$result = $this->findAllQuery($this->tableName);

		$entityList = array();
		while($row = $result->fetchRow()){
			$entity = new EBook($this->api,$row);
			array_push($entityList, $entity);
		}

		return $entityList;
	}


	/**
	 * Saves an item into the database
	 * @param Item $item: the item to be saved
	 * @return the item with the filled in id
	 */
	public function save($ebook){
		$sql = 'INSERT INTO '. $this->tableName . '(fileid, filepath, authors, title, subjects, mtime, '.
				'updated, description, isbn, language, publisher, detailslink, coverlink, thumbnaillink)'.
				' VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

		$params = array(
			$ebook->FileId(),
			$ebook->Path(),
			json_encode($ebook->Authors()),
			$ebook->Title(),
			json_encode($ebook->Subjects()),
			$ebook->MTime(),
			$ebook->Updated(),
			$ebook->Description(),
			$ebook->Isbn(),
			$ebook->Language(),
			$ebook->Publisher(),
			$ebook->DetailsLink(),
			$ebook->CoverLink(),
			$ebook->ThumbnailLink(),
		);

		
		$this->execute($sql, $params);

		$ebook->setId($this->api->getInsertId($this->tableName));
		\OC_Log::write("EBookMappe::saver", "Inserted, ID = ".$ebook->getId(),4);
	}


	/**
	 * Updates an item
	 * @param Item $item: the item to be updated
	 */
	public function update($ebook){
		$sql = 'UPDATE '. $this->tableName . ' SET
			fileid = ?,
			filepath = ?,
			authors = ?,
			title = ?,
			subjects = ?,
			mtime = ?,
			updated = ?,
			description = ?,
			isbn = ?,
			language = ?,
			publisher = ?,
			detailslink = ?,
			coverlink = ?,
			thumbnaillink = ?
			WHERE id = ?';

		$params = array(
			$ebook->FileId(),
			$ebook->Path(),
			json_encode($ebook->Authors()),
			$ebook->Title(),
			json_encode($ebook->Subjects()),
			$ebook->MTime(),
			$ebook->Updated(),
			$ebook->Description(),
			$ebook->Isbn(),
			$ebook->Language(),
			$ebook->Publisher(),
			$ebook->DetailsLink(),
			$ebook->CoverLink(),
			$ebook->ThumbnailLink(),
			$ebook->getId(),
		);

		$this->execute($sql, $params);
	}


	/**
	 * Deletes an item
	 * @param int $id: the id of the item
	 */
	public function delete($id){
		$this->deleteQuery($this->tableName, $id);
	}


}