<?php
class ProductListing extends ListClass{
	
	private $PAGE_ID = '18';
	
	function __construct($URL){
		//DEFAULT VALUES //
		global  $CONFIG;
		
		parent::__construct($this->PAGE_ID);
		$this->data = $this->GetData();
	}
	

}