<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Employee extends Model
{
    //

    protected $fillable = ['name', 'national_id', 'join_date', 'salary'];


    public static function getDups(){

        $pes= Employee::get('national_id');
      return  $dups = DB::table('pending_employees')->whereIn('national_id', $pes)->get();

    }
}
