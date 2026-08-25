<?php
namespace App\Models\Hrms;
use Illuminate\Database\Eloquent\Model;
class TrainingNeedAnalysis extends Model { protected $table='training_need_analysis'; protected $guarded=[]; protected $casts=['tna_date'=>'date']; }
