<?php

namespace OCA\Library\Lib;

use \OCA\AppFramework\Http\Response as Response;


require_once __DIR__ . '/../3rdparty/php-epub-meta-master/epub.php';

//FIXME This would need to be API ified.
class Cover extends Response {
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

	public static function clear($api, $ebook) {
		$view = \OCP\Files::getStorage('library');
		$thumnail_file = $ebook->getId().".thmb";
		if ($view->file_exists($thumnail_file)) {
			$view->unlink($thumnail_file);
		}
		$cover_file = $ebook->getId().".cvr";
		if ($view->file_exists($cover_file)) {
			$view->unlink($cover_file);
		}
		
	}
	public static function getThumbnail($api, $ebook) {
		
		$view = \OCP\Files::getStorage('library');

		$cover_file = $ebook->getId().".thmb";
		if ($view->file_exists($cover_file)) {
			$image = new \OC_Image($view->fopen($cover_file, 'r'));
		}else {
			$localFile = $api->getLocalFile($ebook->Path());
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

	public static function getCover($api, $ebook) {

		$view = \OCP\Files::getStorage('library');

		$cover_file = $ebook->getId().".cvr";
		if ($view->file_exists($cover_file)) {
			$image = new \OC_Image($view->fopen($cover_file, 'r'));
		}else {
			$localFile = $api->getLocalFile($ebook->Path());
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

