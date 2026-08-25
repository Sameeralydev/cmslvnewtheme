<?php
namespace App\Models\Hrms;
use Illuminate\Database\Eloquent\Model;
class TrainingAgenda extends Model { protected $table='training_agenda'; protected $guarded=[]; protected $casts=['agenda_items'=>'array']; }
