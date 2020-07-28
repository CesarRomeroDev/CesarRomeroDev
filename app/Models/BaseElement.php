<?php
namespace App\Models;

class BaseElement {
	protected $title;
	public $description;
	public $visible = true;
	public $months;

	public function __construct($title, $description, $visible,$months){
		$this->setTitle($title);
		$this->description = $description;
		$this->visible = $visible;
		$this->months = $months;
	}

	public function setTitle($t){
		if ($t == '') {
			$this->title = 'N/A';
		} else {
		    $this->title = $t;
		}
	}

	public function getTitle(){
		return $this->title;
	}

	public function getDurationAsString(){
   $years = floor($this->months / 12);
   $extraMonths = $this->months % 12;
   if ($years == 0) {
    return "$extraMonths months";
   }
  return "$years years $extraMonths months";
   }

   public function getDescription() {
   	return $this->description;
   }

}