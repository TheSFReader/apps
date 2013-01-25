<?php

namespace OCA\Library\Lib;

class HookHandler {
	public static function writeFile($params) {
		/*
		$path = $params[OC_Filesystem::signal_param_path];
		if (self::isPhoto($path)) {
			OCP\Util::writeLog('gallery', 'updating thumbnail for ' . $path, OCP\Util::DEBUG);
			\OC\Pictures\ThumbnailsManager::getInstance()->getThumbnail($path);
		}
		*/
		$path = $params[\OC_Filesystem::signal_param_path];
		\OC_Log::write("HookHandler", "writeFile " . $path,4);
		
		
	}
	
	public static function removeFile($params) {
		\OC_Log::write("HookHandler", "removeFile " . var_export($params, true),4);
		//\OC\Pictures\ThumbnailsManager::getInstance()->delete($params[\OC_Filesystem::signal_param_path]);
	}
	
	public static function renameFile($params) {
		\OC_Log::write("HookHandler", "renameFile " . var_export($params, true),4);
		$oldpath = $params[\OC_Filesystem::signal_param_oldpath];
		$newpath = $params[\OC_Filesystem::signal_param_newpath];
		//TODO: implement this
	}
}