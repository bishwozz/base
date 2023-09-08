<?php

namespace App\Models;

use App\Base\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notifications extends BaseModel
{
    use HasFactory;

    protected $table = 'notifications';
    
    protected $status = [ 0 => 'Reject', 1 => 'Approved'];
    protected $fillable = ['roles_id','status_id','user_id','agenda_id','ministry_id',
        'type','data','read_at','from_role_name','from_user_name','meeting_request_id', 'meeting_minute_id'];
}
