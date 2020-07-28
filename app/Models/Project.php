<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model{
	protected $table = 'project';

	public function getDurationAsString(){
   $years = floor($this->months / 12);
   $extraMonths = $this->months % 12;
   if ($years == 0) {
    return "$extraMonths months";
   }
  return "Job duration: $years years $extraMonths months";
   }
}