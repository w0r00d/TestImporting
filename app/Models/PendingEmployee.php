<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingEmployee extends Model
{
    //
    protected $fillable = ['name', 'national_id', 'join_date', 'salary'];

    public static function getDups()
    {

        $pen = PendingEmployee::get('national_id'); // getting emps to find their duplicates

        $dups = Employee::whereIn('national_id', $pen)->get('national_id')->union($pen); // getting the employees with same nid
        $dups2 = Employee::whereIn('national_id', $pen);
        $d = EmpView::whereIn('national_id', $dups)->orderBy('national_id');

        return $d;

    }

    public static function getNotDups()
    {

        $pes = PendingEmployee::get('national_id');

        return $dups = Employee::whereNotIn('national_id', $pes);

    }
}
