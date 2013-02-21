<?php

namespace OCA\Library\Db;


Class Author {
	protected $api;
	protected $authorId;
	protected $name;
	protected $nameas;
	
	function __construct($api, $path, $as=null) {
		
		if(is_array($path)) {
			$this->fromRow($api,$path);
			return;
		}
		
		$this->api = $api;
		$this->name = $path;
		if($as === null)
			$as = $name;
		$this->nameas = $as;
		$this->authorId=-1;
	}
	
	function fromRow($api, $row) {
		$this->api = $api;
		$this->authorId =$row['id'];
		$this->nameas =$row['nameas'];
		$this->name =$row['name'];

	}
	
	
	public function getId(){
		return $this->authorId;
	}
	
	public function setId($id){
		$this->authorId = $id;
	}
	
	
	
	public function Name($name = false) {
		if($name!== false) {
			$this->name = $name;
		}
		return $this->name;
	}
	public function NameAs($nameas = false) {
		if($nameas!== false) {
			$this->nameas = $nameas;
		}
		return $this->nameas;
	}
	
	
	
}