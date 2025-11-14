<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    use HasFactory;

    protected $table = 'unit_kerja';

    protected $fillable = [
        'nama',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    // Relationship dengan User
    public function users()
    {
        return $this->hasMany(User::class, 'unit_kerja', 'nama');
    }

    // Scope untuk unit kerja aktif
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }
}