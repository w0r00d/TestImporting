<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Employee;
class PendingEmployee extends Model
{
    //
    protected $fillable = ['name', 'national_id', 'join_date', 'salary'];

    public static function getDups(){

        $pes= PendingEmployee::get('national_id');
      return  $dups = Employee::whereIn('national_id', $pes);

    }

    public static function getNotDups() {

        $pes= PendingEmployee::get('national_id');
       return $dups = Employee::whereNotIn('national_id', $pes);
       
    }
}
