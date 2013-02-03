<?php

namespace OCA\Library\Lib;

use OCA\Library\Db\EBook;
use OCA\Library\Db\EBookMapper;
use OCA\Library\DependencyInjection\DIContainer;
use OCA\Library\Lib\Cover;

class HookHandler {
	
	protected $api;
	protected $ebookMapper;
	/**
	 * @param Request $request: an instance of the request
	 * @param API $api: an api wrapper instance
	 * @param EBookMapper $ebookMapper: an ebookMapper instance
	 */
	public function __construct($api, $ebookMapper){
		$this->api=$api;
		$this->ebookMapper = $ebookMapper;
	}
	
	public static function writeFile($params) {
		$diContainer = new DIContainer();
		$api = $diContainer['API'];
		
		$path = $params[\OC\Files\Filesystem::signal_param_path];
		
		$api->log("Adding $path");
		if(isset($path) && $path !== '') {
			
			$ebook = new EBook($api,  $path);
			$userId = $api->getUserId();
			$ebookMapper = $diContainer['EBookMapper'];
			$ebookMapper->save($ebook,$userId);
			// We update so that it stores the updated links
			$ebookMapper->update($ebook,$userId);
		}
	}
	
	public static function removeFile($params) {
		
		
		$path = $params[\OC\Files\Filesystem::signal_param_path];
		try { 
			$diContainer = new DIContainer();
			$api = $diContainer['API'];

			$api->log("Removing $path");
			
			$mapper = new EBookMapper ($api);
			$userId = $api->getUserId();
			$ebook = $mapper->findByPathAndUserId($path,$userId);
			
			$cover= new Cover($api,$ebook);
			$cover->clearImages();
			
			$userId = $api->getUserId();
			$ebookMapper = $diContainer['EBookMapper'];
			$ebookMapper->deleteByPath($path,$userId);
		} catch(DoesNotExistException $ex) {
			\OC_Log::write("HookHandler", "Caught! " . var_export($params, true),4);
		}
		
		
	}
	
	public static function renameFile($params) {
		
		
		$oldpath = $params[\OC\Files\Filesystem::signal_param_oldpath];
		$newpath = $params[\OC\Files\Filesystem::signal_param_newpath];
		
		$diContainer = new DIContainer();
		$api = $diContainer['API'];
		
		$api->log("Renaming $oldpath to $newpath");
		
		$userId = $api->getUserId();
		$mapper = new EBookMapper ($api);
		
		$ebook = $mapper->findByPathAndUserId($oldpath,$userId);
		Cover::clear($api,$ebook);
		$mapper->updateEbookPath($oldpath, $newpath, $userId);
	}
	
	public static function removeUser($params) {
		$uid = $params['uid'];
		$diContainer = new DIContainer();
		$api = $diContainer['API'];
		$userId = $api->getUserId();
		$ebookMapper = $diContainer['EBookMapper'];
		$results = $ebookMapper->findAllForUser($uid);
		
		foreach($results as $ebook) {
			$ebook = $ebookMapper->findByPathAndUserId($oldpath,$userId);
			Cover::clear($api,$ebook);
			
			$ebookMapper->delete($ebook->getId());
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