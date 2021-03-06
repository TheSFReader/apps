<?php

namespace OCA\Library\Lib;

use OCA\Library\Db\EBook;
use OCA\Library\Db\EBookMapper;
use OCA\Library\DependencyInjection\DIContainer;
use OCA\Library\Lib\Cover;

class HookHandler {
	
	protected $api;
	protected $ebookMapper;
	protected $authorMapper;
	/**
	 * @param Request $request: an instance of the request
	 * @param API $api: an api wrapper instance
	 * @param EBookMapper $ebookMapper: an ebookMapper instance
	 */
	public function __construct($api, $ebookMapper,$authorMapper){
		$this->api=$api;
		$this->ebookMapper = $ebookMapper;
		$this->authorMapper = $authorMapper;
	}
	
	public static function writeFile($params) {
		$diContainer = new DIContainer();
		$api = $diContainer['API'];
		
		$path = $params[\OC\Files\Filesystem::signal_param_path];
		
		if(isset($path) && $path !== '') {
			
			$ebook = new EBook($api,  $path);
			$userId = $api->getUserId();
			$mapper = $diContainer['EBookMapper'];
			$mapper->save($ebook,$userId);
			// We update so that it stores the updated links
			$mapper->update($ebook,$userId);
		}
	}
	
	public static function removeFile($params) {
		
		$path = $params[\OC\Files\Filesystem::signal_param_path];
		try { 
			$diContainer = new DIContainer();
			$api = $diContainer['API'];

			$userId = $api->getUserId();

			$mapper = $diContainer['EBookMapper'];
			$ebook = $mapper->findByPathAndUserId($path,$userId);

			$cover= new Cover($api,$diContainer['LibraryStorage'], $ebook);
			$cover->clearImages();
			
			$mapper->delete($ebook->getId());
		} catch(DoesNotExistException $ex) {
			\OC_Log::write("HookHandler", "Caught! " . var_export($params, true),4);
		}
		
		
	}
	
	public static function renameFile($params) {
		
		
		$oldpath = $params[\OC\Files\Filesystem::signal_param_oldpath];
		$newpath = $params[\OC\Files\Filesystem::signal_param_newpath];
		
		$diContainer = new DIContainer();
		$api = $diContainer['API'];
		
		$userId = $api->getUserId();
		$mapper = $diContainer['EBookMapper'];
		
		$ebook = $mapper->findByPathAndUserId($oldpath,$userId);
		$cover= new Cover($api,$diContainer['LibraryStorage'], $ebook);
		$cover->clearImages();
		$mapper->updateEbookPath($oldpath, $newpath, $userId);
	}
	
	public static function removeUser($params) {
		$uid = $params['uid'];
		$diContainer = new DIContainer();
		$api = $diContainer['API'];
		$userId = $api->getUserId();
		$mapper = $diContainer['EBookMapper'];
		$results = $mapper->findAllForUser($uid);
		
		foreach($results as $ebook) {
			$ebook = $mapper->findByPathAndUserId($oldpath,$userId);
			$cover= new Cover($api,$diContainer['LibraryStorage'], $ebook);
			$cover->clearImages();
			
			$mapper->delete($ebook->getId());
		}
	}
	
	public static function rescan($params) {
		$api = $diContainer['API'];
		$ebookMapper = $diContainer['EBookMapper'];
		$hh = new HookHandler($api, $ebookMapper);
		$hh->rescanImpl();
	}
	
	public function rescanImpl() {
		$fsEPubFiles = $this->api->searchByMime('application/epub+zip');
		$userid=$this->api->getUserId();
		$ebooks = $this->ebookMapper->findAllForUser($userid);
		$ebooksByPaths = array();
		foreach ($ebooks as $ebook) {
			$ebooksByPaths[]=$ebook->Path();
		}
		$epubFilesByPaths = array();
		foreach ($fsEPubFiles as $epubFile) {
			$path = $this->api->getPath($epubFile['fileid']);
			$epubFilesByPaths[]=$path;
		}
		$ebooksWithNoFile = array_diff($ebooksByPaths, $epubFilesByPaths);
		foreach($ebooksWithNoFile as $missingFilePath) {
			$this->ebookMapper->deleteByPath($missingFilePath, $userid);
		}
		$filesWithNoEbook = array_diff($epubFilesByPaths,$ebooksByPaths);
		foreach($filesWithNoEbook as $missingEBookPath) {
			$ebook = new EBook($this->api,  $missingEBookPath);
			$this->ebookMapper->save($ebook,$userid);
			// We update so that it stores the updated links
			$this->ebookMapper->update($ebook,$userid);
		}
	}
}