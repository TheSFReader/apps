<?php

namespace OCA\Library\Lib;

use OCA\Library\Db\EBook;
use OCA\Library\Db\EBookMapper;
use OCA\Library\DependencyInjection\DIContainer;
use OCA\Library\Lib\Cover;

class HookHandler {
	public static function writeFile($params) {
		$path = $params[\OC_Filesystem::signal_param_path];
		if(isset($path) && $path !== '') {
			$diContainer = new DIContainer();
			$api = $diContainer['API'];
			$ebook = new EBook($api,  $path);
			$userId = $api->getUserId();
			$ebookMapper = $diContainer['EBookMapper'];
			$ebookMapper->save($ebook,$userId);
			// We update so that it stores the updated links
			$ebookMapper->update($ebook,$userId);
		}
	}
	
	public static function removeFile($params) {
		
		$path = $params[\OC_Filesystem::signal_param_path];
		try { 
			$diContainer = new DIContainer();
			$api = $diContainer['API'];

			$mapper = new EBookMapper ($api);
			$userId = $api->getUserId();
			$ebook = $mapper->findByPathAndUserId($path,$userId);
			
			Cover::clear($api,$ebook);
			
			$userId = $api->getUserId();
			$ebookMapper = $diContainer['EBookMapper'];
			$ebookMapper->deleteByPath($path,$userId);
		} catch(DoesNotExistException $ex) {
			\OC_Log::write("HookHandler", "Caught! " . var_export($params, true),4);
		}
		
		
	}
	
	public static function renameFile($params) {
		$oldpath = $params[\OC_Filesystem::signal_param_oldpath];
		$newpath = $params[\OC_Filesystem::signal_param_newpath];
		
		$diContainer = new DIContainer();
		$api = $diContainer['API'];
		
		$mapper = new EBookMapper ($api);
		$userId = $api->getUserId();
		$ebook = $mapper->findByPathAndUserId($oldpath,$userId);

		Cover::clear($api,$ebook);
		
		$userId = $api->getUserId();
		$ebookMapper = $diContainer['EBookMapper'];
		$ebookMapper->updateEbookPath($oldpath, $newpath, $userId);
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
}