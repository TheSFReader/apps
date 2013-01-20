<?php

namespace OCA\AppLibrary;

Class EBook {
	protected $api;
	var $id;
	var $path;
	protected $title;
	protected $authors;
	protected $description;
	protected $subjects;
	protected $mtime;
	protected $date;
	protected $formats;
	protected $epub;
	protected $isbn;
	protected $language;
	protected $publisher;
	
	function __construct($api, $path) {
		
		$this->api = $api;
		$this->path = $path;
		$this->id = $this->api->getId($path);
		
		if($this->id === -1) {
			$this->path = $this->api->getPath($path);
			$this->id = $path;
		}
		$localFile = $this->api->getLocalFile($this->path);
		$info = $this->api->getFilesystemInfo($this->path);
		$this->mtime = $info['mtime'];
		
		//FIXME
		$downloadURL=\OCP\Util::linkTo('files', 'ajax/download.php'). '?files='.$path;
		$this->epub = new \EPub($localFile);
		$this->formats = array('epub'=>$downloadURL);
		
		
	}
	
	public function Id(){
		return $this->id;
	}
	
	public function Title($title = false) {
		if($title!== false) {
			$this->title = $title;
		}
		if($this->title === null)
			$this->title=$this->epub->Title();
		return $this->title;
	}
	
	
	public function ISBN($isbn = false) {
		if($isbn!== false) {
			$this->isbn = $isbn;
		}
		if($this->isbn === null)
			$this->isbn=$this->epub->ISBN();
		return $this->isbn;
	}
	
	public function Authors($authors = false) {
		if($authors!== false) {
			$this->authors = $authors;
		}
		if($this->authors === null)
			$this->authors=$this->epub->Authors();
		return $this->authors;
	}
	
	public function Description($description = false) {
		if($description!== false) {
			$this->description = $description;
		}
		if($this->description === null)
			$this->description=$this->epub->Description();
		return $this->description;
	}
	
	public function Subjects($subjects = false) {
		if($subjects!== false) {
			$this->subjects = $subjects;
		}
		if($this->subjects === null)
			$this->subjects=$this->epub->Subjects();
		return $this->subjects;
	}
	
	public function Date($date = false) {
		if($date!== false) {
			$this->date = $date;
		}
		if($this->date === null)
			$this->date=$this->epub->Date();
		return $this->date;
	}
	
	public function Language($language = false) {
		if($language!== false) {
			$this->language = $language;
		}
		if($this->language === null)
			$this->language=$this->epub->Language();
		return $this->language;
	}
	
	public function Publisher($publisher = false) {
		if($publisher!== false) {
			$this->publisher = $publisher;
		}
		if($this->publisher === null)
			$this->publisher=$this->epub->Publisher();
		return $this->publisher;
	}
	
	public function Mtime() {
		
		return $this->mtime;
	}
	
	public function DetailsLink() {
		return $this->api->linkToRoute('library_details', array('id' => $this->id));
	}
	
	public function CoverLink() {
		return $this->api->linkToRoute('library_cover', array('id' => $this->id));
	}
	
	public function ThumbnailLink() {
		return $this->api->linkToRoute('library_thumbnail', array('id' => $this->id));
	}
	
	
}