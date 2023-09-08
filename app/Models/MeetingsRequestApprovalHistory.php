<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingsRequestApprovalHistory extends Model
{
    use HasFactory;

    protected $table = 'meetings_request_approval_history';
    protected $fillable = ['status_id','role_id','meetings_request_id','date_ad','date_bs','remarks'];
    protected $status = [ 0 => 'Reject', 1 => 'Approved'];

}
