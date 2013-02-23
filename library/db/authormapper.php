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


class AuthorMapper extends Mapper {


	private $tableName;
	private $authorsLinkTableName;

	/**
	 * @param API $api: Instance of the API abstraction layer
	 */
	public function __construct(API $api){
		parent::__construct($api);
		$this->tableName = '*PREFIX*library_authors';
		$this->authorsLinkTableName = '*PREFIX*library_ebook_author';
	}


	/**
	 * Finds an item by id
	 * @throws DoesNotExistException: if the item does not exist
	 * @return the item
	 */
	public function find($id){
		$row = $this->findQuery($this->tableName, $id);
		return new Author($this->api, $row);
		
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

		$this->api->log(var_export($sql,true),4);
		$result = $this->execute($sql, $params)->fetchRow();
		if($result){
			return new Author($this->api, $result);
		} else {
			throw new DoesNotExistException('Author with user id ' . $userId . ' does not exist!');
		}
	}

	/**
	 * Finds an author by name
	 * @param string $userId: the id of the user that we want to find
	 * @throws DoesNotExistException: if the item does not exist
	 * @return the item
	 */
	public function findByNameAndUserId($name, $user){
		$sql = 'SELECT * FROM ' . $this->tableName . ' WHERE name = ? and user = ?';
		$params = array($name, $user);
	
		$this->api->log($sql);
		
		$result = $this->execute($sql, $params)->fetchRow();
		if($result){
			return new Author($this->api, $result);
		} else {
			return null;
		}
	}
	/**
	 * Finds an author by as
	 * @param string $userId: the id of the user that we want to find
	 * @throws DoesNotExistException: if the item does not exist
	 * @return the item
	 */
	public function findByAsAndUserId($as, $user){
		$sql = 'SELECT * FROM ' . $this->tableName . ' WHERE nameas = ? and user = ?';
		$params = array($as, $user);
	
		$result = $this->execute($sql, $params)->fetchRow();
		if($result){
			return new Author($this->api, $result);
		} else {
			return null;
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
			$entity = new Author($this->api,$row);
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
	public function findAllForUser($user){
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

		$sql = 'SELECT * FROM ' . $this->tableName . ' WHERE user = ? ORDER BY nameas' ;
		$params = array($user);
		$result= $this->execute($sql,$params);
		
		$entityList = array();
		while($row = $result->fetchRow()){
			$entity = new Author($this->api,$row);
			array_push($entityList, $entity);
		}
		return $entityList;
	}
	
	
	/**
	

	/**
	 * Saves an item into the database
	 * @param Item $item: the item to be saved
	 * @return the item with the filled in id
	 */
	public function save(Author $author, $user){
		$sql = 'INSERT INTO '. $this->tableName . '(user, name, nameas)'.
				' VALUES(?, ?, ?)';

		$params = array(
			$user,
			$author->Name(),
			$author->NameAs(),
		);

		$this->execute($sql, $params);
		$author->setId($this->api->getInsertId($this->tableName));
	}


	/**
	 * Updates an item
	 * @param Item $item: the item to be updated
	 */
	public function update(Author $author){
		$sql = 'UPDATE '. $this->tableName . ' SET
			name = ?,
			nameas = ?
			WHERE id = ?';

		$params = array(
			$author->Name(),
			$author->NameAs(),
			$author->getId()
		);

		$this->execute($sql, $params);
	}


	/**
	 * Deletes an item
	 * @param int $id: the id of the item
	 */
	public function delete($id){
		$sql = 'DELETE FROM `' . $this->authorsLinkTableName . '` WHERE `authorid` = ?';
		$params = array($id);
		$this->execute($sql, $params);
		
		$this->deleteQuery($this->tableName, $id);
	}
	
	/**
	 * delete an author, as specified by its name
	 * @param string $name the name of the author he user whshes to remove
	 */
	public function deleteByName($name, $user){
		$sql = 'SELECT id FROM ' . $this->tableName . ' WHERE name = ? and user = ?';
		$params = array($name, $user);
		
		$result = $this->execute($sql, $params)->fetchRow();
		if($result){
			$id = $result['id'];
			$sql = 'DELETE FROM `' . $this->authorsLinkTableName . '` WHERE `authorid` = ?';
			$params = array($id);
			$this->execute($sql, $params);
			
			$sql = 'DELETE FROM `' . $this->tableName . '` WHERE `id` = ?';
			$params = array($id);
			$this->execute($sql, $params);
		}
	}
	
	/**
	 * delete an author, as specified by its "as" name
	 * @param string $name the name of the author he user whshes to remove
	 */
	public function deleteByNameAs($nameas, $user){
		$sql = 'SELECT id FROM ' . $this->tableName . ' WHERE nameas = ? and user = ?';
		$params = array($nameas, $user);
		
		$result = $this->execute($sql, $params)->fetchRow();
		if($result){
			$id = $result['id'];
			$sql = 'DELETE FROM `' . $this->authorsLinkTableName . '` WHERE `authorid` = ?';
			$params = array($id);
			$this->execute($sql, $params);
			
			$sql = 'DELETE FROM `' . $this->tableName . '` WHERE `id` = ?';
			$params = array($id);
			$this->execute($sql, $params);
		}
		
	}
	
	public function addEBookLink(EBook $ebook, Author $author,$user) {
		
		if($author->getId() == null || $author->getId() <0) {
			$author2 = ($this->findByAsAndUserId($author->NameAs(),$user));
			if($author2 == null) {
				$this->save($author,$user);
			}
			else {
				$author->setId($author2->getId());
				$this->update($author,$user);
			}
		}
		if($ebook->getId() < 0)
			return;
		
		
		$sql = 'SELECT * FROM ' . $this->authorsLinkTableName . ' WHERE ebookid = ? and authorid = ?';
		$params = array($ebook->getId(), $author->getId());
		
		$result = $this->execute($sql, $params)->fetchRow();
		if($result)
			return;
		
		$sql = 'INSERT INTO '. $this->authorsLinkTableName . '(ebookid, authorid)'.
				' VALUES(?, ?)';

		$params = array(
			$ebook->getId(),
			$author->getId(),
		);
		$this->execute($sql, $params);
		
	}
	
	
}