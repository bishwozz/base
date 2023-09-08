<?php

namespace App\Models;

use App\Base\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MeetingMinuteApprovalHistory extends BaseModel
{
    use HasFactory;

    protected $table = 'meeting_minute_approval_history';
    // protected $fillable = ['status_id','role_id','meeting_minute_id','date_ad','date_bs'];
    
    protected $status = [ 0 => 'Reject', 1 => 'Approved'];
}
