<?php

namespace OCA\Library\Lib;

use OCA\Library\Db\EBook as EBook;
use OCA\Library\Db\EBookMapper as EBookMapper;
use OCA\Library\DependencyInjection\DIContainer as DIContainer;

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
		}
	}
	
	public static function removeFile($params) {
		
		$path = $params[\OC_Filesystem::signal_param_path];
		try { 
			$diContainer = new DIContainer();
			$api = $diContainer['API'];

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
			$ebookMapper->delete($ebook->getId());
		}
	}
}