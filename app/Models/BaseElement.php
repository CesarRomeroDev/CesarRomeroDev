<?php
namespace App\Models;

class BaseElement {
	protected $title;
	public $description;
	public $visible;
	public $months;

	public function __construct($title, $description){
		$this->title = $title;
		$this->description = $description;
	}
}