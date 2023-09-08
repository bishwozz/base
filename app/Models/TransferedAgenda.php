<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferedAgenda extends Model
{
    use HasFactory;
    protected $table = 'transfered_agendas';

    public function ministry_entity(){
        return $this->belongsTo('App\Models\Ministry','ministry_id','id');
    }
    public function agenda(){
        return $this->belongsTo('App\Models\Agenda','agenda_id','id');
    }
    public function committee(){
        return $this->belongsTo('App\Models\Committee','committee_id','id');
    }
}
