<?php

class CSV
{
   private $filename;
   private $headers = array();
   private $file_container;
   
   function __construct($filename)
   {
	   $this->filename = $filename;
   }
   
   function open(){
	  $this->file_container = fopen($this->filename,'w');   
   }
   
   function close(){
	  fclose($this->file_container);  
   }
   function save_header($header){
	  fputcsv($this->file_container,$header);   
   }
   function save_line($array){
	   fputcsv($this->file_container,$array);
   }
}

?>