<?php

namespace OCA\AppLibrary;


//FIXME This would need to be API ified.
class Cover extends \OCA\AppFramework\Response {
	protected $image;
	
	function __construct($image) {
		parent::__construct();
		$this->image = $image;
	}
	function render() {
		parent::render();
		if($this->image !== null)
			$this->image->show();
	}
	
	public static function getThumbnail($api, $image_path) {
		
		$localFile = \OC_Filesystem::getLocalFile($image_path);
		$view = \OCP\Files::getStorage('library');

		$cover_file = basename($image_path).".thmb";
		if ($view->file_exists($cover_file)) {
			$image = new \OC_Image($view->fopen($cover_file, 'r'));
		}else {
			$epub = @new \EPub($localFile);
			$cover = $epub->Cover();
			$image=new \OC_Image($cover['data']);
			$image->fixOrientation();
			$image->resize(200);
			$image->save($view->getLocalFile($cover_file));
		}


		if ($image->valid()) {
			return new Cover($image);
		} else {
			$image->destroy();
		}
		return null;
	}

	public static function getCover($api, $image_path) {

		$localFile = \OC_Filesystem::getLocalFile($image_path);
		$view = \OCP\Files::getStorage('library');

		$cover_file = basename($image_path).".cvr";
		if ($view->file_exists($cover_file)) {
			$image = new \OC_Image($view->fopen($cover_file, 'r'));
		}else {
			$epub = @new \EPub($localFile);
			$cover = $epub->Cover();
			$image=new \OC_Image($cover['data']);
			$image->fixOrientation();
			$image->save($view->getLocalFile($cover_file));
		}

		if ($image->valid()) {
			return new Cover($image);
		} else {
			$image->destroy();
		}
		return null;
	}



}

