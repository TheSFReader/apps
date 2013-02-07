<?php

namespace OCA\Library\Lib;


require_once __DIR__ . '/../3rdparty/php-epub-meta-master/epub.php';

class Cover {
	protected $api;
	protected $ebook;
	protected $libraryStorage;
	
	function __construct($api, $libraryStorage, $ebook) {
		$this->api = $api;
		$this->ebook = $ebook;
		$this->libraryStorage = $libraryStorage;
	}
	

	public function clearImages() {
		$thumnail_file = $this->ebook->getId().".thmb";
		if ($this->libraryStorage->file_exists($thumnail_file)) {
			$this->libraryStorage->unlink($thumnail_file);
		}
		$cover_file = $this->ebook->getId().".cvr";
		if ($this->libraryStorage->file_exists($cover_file)) {
			$this->libraryStorage->unlink($cover_file);
		}
		
	}
	public function getThumbnailImage() {
		
		
		$cover_file = $this->ebook->getId().".thmb";
		if ($this->libraryStorage->file_exists($cover_file)) {
			$file = $this->libraryStorage->fopen($cover_file, 'r');
			$image = $this->api->createImage($file);
		}else {
			$localFile = $this->api->getLocalFile($this->ebook->Path());
			$epub = @new \EPub($localFile);
			$cover = $epub->Cover();
			
			$image= $this->api->createImage($cover['data']);
			if($image->width() > 1) {
				$image->fixOrientation();
				$image->resize(200);
			}
			$image->save($this->libraryStorage->getLocalFile($cover_file));
		}


		if ($image->valid()) {
			return $image;
		} else {
			$image->destroy();
		}
		return null;
	}

	public function getCoverImage() {

		
		$cover_file = $this->ebook->getId().".cvr";
		if ($this->libraryStorage->file_exists($cover_file)) {
			$image = $this->api->createImage($this->libraryStorage->fopen($cover_file, 'r'));
		}else {
			$localFile = $this->api->getLocalFile($this->ebook->Path());
			$epub = @new \EPub($localFile);
			$cover = $epub->Cover();
			$image= $this->api->createImage($cover['data']);
			$image->fixOrientation();
			$image->save($this->libraryStorage->getLocalFile($cover_file));
		}

		if ($image->valid()) {
			return $image;
		} else {
			$image->destroy();
		}
		return null;
	}



}

