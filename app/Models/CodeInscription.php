<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeInscription extends Model
{
    use HasFactory;

    protected $table = 'codes_inscription';

    protected $fillable = [
        'code', 'name', 'email', 'role', 'utilise', 'cree_par'
    ];

    public function creePar()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }
} 