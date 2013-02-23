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

namespace OCA\Library\Db;

use \OCA\AppFramework\Core\API;
use \OCA\AppFramework\Db\Mapper;
use \OCA\AppFramework\Db\DoesNotExistException;


class EBookMapper extends Mapper {


	private $tableName;
	private $authorsLinkTableName;
	private $authorMapper;

	/**
	 * @param API $api: Instance of the API abstraction layer
	 */
	public function __construct(API $api, AuthorMapper $authorMapper){
		parent::__construct($api);
		$this->tableName = '*PREFIX*library_ebooks';
		$this->authorsLinkTableName = '*PREFIX*library_ebook_author';
		$this->authorMapper = $authorMapper;
	}


	/**
	 * Finds an item by id
	 * @throws DoesNotExistException: if the item does not exist
	 * @return the item
	 */
	public function find($id){
		$row = $this->findQuery($this->tableName, $id);
		return new EBook($this->api, $row);
		
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
			return new EBook($this->api, $result);
		} else {
			throw new DoesNotExistException('EBook with user id ' . $userId . ' does not exist!');
		}
	}

	/**
	 * Finds an ebook by local path
	 * @param string $userId: the id of the user that we want to find
	 * @throws DoesNotExistException: if the item does not exist
	 * @return the item
	 */
	public function findByPathAndUserId($path, $user){
		$sql = 'SELECT * FROM ' . $this->tableName . ' WHERE filepath = ? and user = ?';
		$params = array($path, $user);
	
		$result = $this->execute($sql, $params)->fetchRow();
		if($result){
			return new EBook($this->api, $result);
		} else {
			throw new DoesNotExistException("EBook with path $path does not exist for user $user!");
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
	 * Finds all Items for a given user
	 * @param $user the userId
	 * @param $sortby (or null) the sorting criteria to be used ammongst:
	 * title, newest, authors,publlisher
	 * @return array containing all items
	 */
	public function findAllForUser($user, $sortby = null){
		$descending = false;
		$paramName = 'title';
			
		if(isset($sortby)) {
			if($sortby =='newest') {
				$paramName = 'mtime';
				$descending = true;
			} elseif ($sortby =='authors') {
				$paramName = 'authors';
			} elseif ($sortby =='publisher') {
				$paramName = 'publisher';
			} elseif ($sortby =='title') {
				$paramName = 'title';
			}	
		}

		
		$sql = 'SELECT * FROM ' . $this->tableName . ' WHERE user = ? ORDER BY '.$paramName ;
		if($descending)
			$sql .= ' DESC';
		$params = array($user);
		$result= $this->execute($sql,$params);
		
		$entityList = array();
		while($row = $result->fetchRow()){
			$entity = new EBook($this->api,$row);
			array_push($entityList, $entity);
		}
		return $entityList;
	}
	
	/**
	 * Finds all Items for a given user
	 * @param $user the userId
	 * @param $sortby (or null) the sorting criteria to be used ammongst:
	 * title, newest, authors,publlisher
	 * @return array containing all items
	 */
	public function findAllForUserAuthor($user, $author, $sortby = null){
		
		
		$descending = false;
		$paramName = 'title';
			
		if(isset($sortby)) {
			if($sortby =='newest') {
				$paramName = 'mtime';
				$descending = true;
			} elseif ($sortby =='authors') {
				$paramName = 'authors';
			} elseif ($sortby =='publisher') {
				$paramName = 'publisher';
			} elseif ($sortby =='title') {
				$paramName = 'title';
			}
		}
	
	
		$tableName = $this->tableName;
		$authorsLinkTableName = $this->authorsLinkTableName;
		$sql = "SELECT * FROM '$tableName','$authorsLinkTableName' WHERE user = ? AND authorid = ? AND $tableName.id = $authorsLinkTableName.ebookid ORDER BY $paramName";
		if($descending)
			$sql .= ' DESC';
		$params = array($user, $author);
		$result= $this->execute($sql,$params);
	
		$entityList = array();
		while($row = $result->fetchRow()){
			$entity = new EBook($this->api,$row);
			array_push($entityList, $entity);
		}
		return $entityList;
	}
	
	/**
	 * Findsthe latest
	 * @return array containing all items
	 */
	public function latestMTime($user) {
		$sql = 'SELECT MAX(mtime) as maxMTime FROM ' . $this->tableName . ' WHERE user = ?' ;
		$params = array($user);
		$result= $this->execute($sql,$params)->fetchrow();
		
		return $result['maxMTime'];
	}


	/**
	 * Saves an item into the database
	 * @param Item $item: the item to be saved
	 * @return the item with the filled in id
	 */
	public function save(EBook $ebook, $user){
		$sql = 'INSERT INTO '. $this->tableName . '(user, filepath, authors, title, subjects, mtime, '.
				'updated, description, isbn, language, publisher, detailslink, coverlink, thumbnaillink, imagesizes)'.
				' VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

		$params = array(
			$user,
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
			json_encode($ebook->ImageSizes()),
		);

		
		$this->execute($sql, $params);

		$ebook->setId($this->api->getInsertId($this->tableName));

		foreach ($ebook->Authors() as $as => $authorname) {
			$author = new Author($this->api, $authorname, $as);
			$this->authorMapper->addEBookLink($ebook, $author, $user);
		}
	}


	/**
	 * Updates an item
	 * @param Item $item: the item to be updated
	 */
	public function update(EBook $ebook, $user){
		$sql = 'UPDATE '. $this->tableName . ' SET
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
			thumbnaillink = ?,
			imagesizes = ?
			WHERE id = ?';

		$params = array(
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
			json_encode($ebook->ImageSizes()),
				
			$ebook->getId(),
		);

		$this->execute($sql, $params);
		
		// Keep the authors in the ebook's table for cache.
		$sql = 'DELETE FROM `' . $this->authorsLinkTableName . '` WHERE `ebookid` = ?';
		$params = array($ebook->getId());
		$this->execute($sql, $params);
		
		foreach ($ebook->Authors() as $as => $authorname) {
			$author = new Author($this->api, $authorname, $as);
			$this->authorMapper->addEBookLink($ebook, $author, $user);
		
		}
		
	}


	/**
	 * Deletes an item
	 * @param int $id: the id of the item
	 */
	public function delete($id){
		$this->deleteQuery($this->tableName, $id);
	}
	
	/**
	 * delete an ebook, as specified by its local path
	 * @param string $path the path of the ebook he user whshes to remove
	 */
	public function deleteByPath($path, $user){
		$sql = 'DELETE FROM `' . $this->tableName . '` WHERE `filepath` = ? and user = ?';
		$params = array($path, $user);
		$this->execute($sql, $params);
	}
	
	
	
	/**
	 * change an ebook's path
	 * @param string $userId: the id of the user that we want to find
	 * @throws DoesNotExistException: if the item does not exist
	 * @return the item
	 */
	public function updateEbookPath($oldpath, $newpath, $user){
		$sql = 'UPDATE '. $this->tableName . ' SET filepath = ? WHERE filepath = ? and user = ?';
		$params = array($newpath, $oldpath,$user);
		$this->execute($sql, $params);
	}


}