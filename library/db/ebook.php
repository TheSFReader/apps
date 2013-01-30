<?php

namespace OCA\Library\Db;

require_once __DIR__ . '/../3rdparty/php-epub-meta-master/epub.php';

Class EBook {
	protected $api;
	protected $ebookId;
	protected $fileId;
	protected $path;
	protected $title;
	protected $authors;
	protected $description;
	protected $subjects;
	protected $mtime;
	protected $updated;
	protected $date;
	protected $formats;
	protected $epub;
	protected $isbn;
	protected $language;
	protected $publisher;
	protected $detailsLink;
	protected $coverLink;
	protected $thumbnailLink;
	
	function __construct($api, $path) {
		
		if(is_array($path)) {
			$this->fromRow($api,$path);
			return;
		}
		
		$this->api = $api;
		$this->path = $path;
		$this->fileId = $this->api->getId($path);
		
		if($this->fileId === -1) {
			$this->path = $this->api->getPath($path);
			$this->fileId = $path;
		}
		$this->ebookId=$this->fileId;
		$localFile = $this->api->getLocalFile($this->path);
		$info = $this->api->getFilesystemInfo($this->path);
		$this->mtime = $info['mtime'];
		$dt = new \DateTime();
		$dt->setTimestamp($this->mtime);
		$this->updated = $dt->format(\DateTime::ATOM);
		
		
		//FIXME
		$downloadURL=$this->api->linkToAbsolute('ajax/download.php','files', array('files' => $this->path));
		$this->formats = array('epub'=>$downloadURL);
		
		$this->thumbnailLink = $this->api->linkToRouteAbsolute('library_thumbnail', array('id' => $this->fileId));
		$this->coverLink = $this->api->linkToRouteAbsolute('library_cover', array('id' => $this->fileId));
		$this->detailsLink = $this->api->linkToRouteAbsolute('library_details', array('id' => $this->fileId));
		

		$this->epub = new \EPub($localFile);
		
		
	}
	
	function fromRow($api, $row) {
		$this->api = $api;
		$this->ebookId =$row['id'];
		$this->fileId =$row['fileid'];
		$this->path =$row['filepath'];
		$this->authors = json_decode ($row['authors'], true);
		$this->title =$row['title'];
		$this->subjects = json_decode ($row['subjects'], true);
		$this->mtime =$row['mtime'];
		$this->updated =$row['updated'];
		$this->description =$row['description'];
		$this->isbn =$row['isbn'];
		$this->language =$row['language'];
		$this->publisher =$row['publisher'];
		$this->detailsLink =$row['detailsLink'];
		$this->coverLink =$row['coverLink'];
		$this->thumbnailLink =$row['thumbnailLink'];
		
		//FIXME
		$downloadURL=$this->api->linkToAbsolute('ajax/download.php','files', array('files' => $this->path));
		$this->formats = array('epub'=>$downloadURL);
		

		$localFile = $this->api->getLocalFile($this->path);
		$this->epub = new \EPub($localFile);
		

	}
	
	
	
	
	
	
	
	public function getId(){
		return $this->ebookId;
	}
	
	public function setId($id){
		$this->ebookId = $id;
	}
	
	public function FileId(){
		return $this->fileId;
	}
	
	public function Path(){
		return $this->path;
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
	
	public function Updated() {
		return $this->updated;
	}
	
	public function DetailsLink() {
		return $this->detailsLink;
	}
	
	public function CoverLink() {
		return $this->coverLink;
	}
	
	public function ThumbnailLink() {
		return $this->thumbnailLink;
	}
	
	public function Formats() {
		return  $this->formats;
	}
	
	
}