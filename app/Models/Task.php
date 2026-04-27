<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    
    use HasFactory;

    /**
          *
     * @var array<string>
     * The <string> in the comment means this is an array of strings
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'due_date',
    ];

    /**
     
     *
     * @var array<string, string>
     */
    protected $casts = [
        'due_date' => 'datetime',
    ];

   
    public function user()
    {
        return $this->belongsTo(User::class);
      
    }
}