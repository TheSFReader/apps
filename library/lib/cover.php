<?php

namespace OCA\Library\Lib;


require_once __DIR__ . '/../3rdparty/php-epub-meta-master/epub.php';

//FIXME This would need to be API ified.
class Cover {
	protected $api;
	protected $ebook;
	
	function __construct($api, $ebook) {
		$this->api = $api;
		$this->ebook = $ebook;
	}
	

	public function clearImages() {
		$view = \OCP\Files::getStorage('library');
		$thumnail_file = $this->ebook->getId().".thmb";
		if ($view->file_exists($thumnail_file)) {
			$view->unlink($thumnail_file);
		}
		$cover_file = $this->ebook->getId().".cvr";
		if ($view->file_exists($cover_file)) {
			$view->unlink($cover_file);
		}
		
	}
	public function getThumbnailImage() {
		
		$view = \OCP\Files::getStorage('library');

		$cover_file = $this->ebook->getId().".thmb";
		if ($view->file_exists($cover_file)) {
			$file = $view->fopen($cover_file, 'r');
			$image = new \OC_Image($file);
		}else {
			$localFile = $this->api->getLocalFile($this->ebook->Path());
			$epub = @new \EPub($localFile);
			$cover = $epub->Cover();
			$this->api->log(var_export($cover,true));
			
			$image=new \OC_Image($cover['data']);
			if($image->width() > 1) {
				$image->fixOrientation();
				$image->resize(200);
			}
			$image->save($view->getLocalFile($cover_file));
		}


		if ($image->valid()) {
			return $image;
		} else {
			$image->destroy();
		}
		return null;
	}

	public function getCoverImage() {

		$view = \OCP\Files::getStorage('library');

		$cover_file = $this->ebook->getId().".cvr";
		if ($view->file_exists($cover_file)) {
			$image = new \OC_Image($view->fopen($cover_file, 'r'));
		}else {
			$localFile = $this->api->getLocalFile($this->ebook->Path());
			$epub = @new \EPub($localFile);
			$cover = $epub->Cover();
			$image=new \OC_Image($cover['data']);
			$image->fixOrientation();
			$image->save($view->getLocalFile($cover_file));
		}

		if ($image->valid()) {
			return $image;
		} else {
			$image->destroy();
		}
		return null;
	}



}

