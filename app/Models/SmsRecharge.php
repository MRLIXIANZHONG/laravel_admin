<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */

namespace App\Models;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Mockery\Exception;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as AuthUser;

//laravel伪删除类

class SmsRecharge extends BaseModel {
   protected $table ='sms_recharge';



}
