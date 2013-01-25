<?php

namespace OCA\Library\Lib;

use OCA\Library\Db\EBook as EBook;
use OCA\Library\Db\EBookMapper as EBookMapper;
use OCA\Library\DependencyInjection\DIContainer as DIContainer;

class HookHandler {
	public static function writeFile($params) {
		$path = $params[\OC_Filesystem::signal_param_path];
		//\OC_Log::write("HookHandler", "writeFile '$path'",4);
		if(isset($path) && $path !== '') {
			$diContainer = new DIContainer();
			$api = $diContainer['API'];
			$ebook = new EBook($api,  $path);
			
			$ebookMapper = $diContainer['EBookMapper'];
			$ebookMapper->save($ebook);
		}
		
		
	}
	
	public static function removeFile($params) {
		\OC_Log::write("HookHandler", "removeFile " . var_export($params, true),4);
		
		$path = $params[\OC_Filesystem::signal_param_path];
		
		try { 
		}catch(DoesNotExistException $ex) {
		\OC_Log::write("HookHandler", "Caught! " . var_export($params, true),4);
		}
		//\OC_Log::write("HookHandler", "writeFile '$path'",4);
		/*if(isset($path) && $path !== '') {
			$diContainer = new DIContainer();
			$api = $diContainer['API'];
			$ebook = new EBook($api,  $path);
			
			$ebookMapper = $diContainer['EBookMapper'];
			try {
			$ebook = ebookMapper->find
			}
			$ebookMapper->delete($ebook-);
		}*/
	}
	
	public static function renameFile($params) {
		\OC_Log::write("HookHandler", "renameFile " . var_export($params, true),4);
		$oldpath = $params[\OC_Filesystem::signal_param_oldpath];
		$newpath = $params[\OC_Filesystem::signal_param_newpath];
		//TODO: implement this
	}
}