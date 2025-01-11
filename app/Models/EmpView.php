<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class EmpView extends Model
{
    //
    protected $table = 'emp_view';
    protected $fillable = ['name', 'national_id', 'join_date', 'salary'];
public static function test(){

    return EmpView::all();
}
    public static function getDups(){

        return DB::table('emp_view')
        ->selectRaw('
            COUNT(*) AS duplicate_count,
            national_id, name  
        ')
        ->groupBy('national_id')
        ->having('duplicate_count', '>', 1)
        ->orderByDesc('duplicate_count')
        ->orderBy('national_id')
        ->get();
    }
}
