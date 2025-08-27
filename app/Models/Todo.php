<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    /** @use HasFactory<\Database\Factories\TodoFactory> */
    use HasFactory;

    protected $fillable = ['title', 'status', 'due_date', 'assign_emp_id'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'assign_emp_id');
    }
}
