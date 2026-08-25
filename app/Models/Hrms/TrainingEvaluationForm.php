<?php
namespace App\Models\Hrms;
use Illuminate\Database\Eloquent\Model;
class TrainingEvaluationForm extends Model { protected $table='training_evaluation_form'; protected $guarded=[]; protected $casts=['date_of_event'=>'date']; }
