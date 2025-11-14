<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'name',
        'status',
        'position_x',
        'position_y',
        'notes',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function openTickets()
    {
        return $this->tickets()->whereIn('status', ['open', 'in_progress']);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'active' => '#22c55e',
            'offline' => '#ef4444',
            'maintenance' => '#eab308',
            default => '#6b7280',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active' => 'Aktif',
            'offline' => 'Offline',
            'maintenance' => 'Maintenance',
            default => 'Unknown',
        };
    }

    public function hasOpenTicket(): bool
    {
        return $this->openTickets()->exists();
    }

    public function getAbsolutePosition(): array
    {
        return [
            'x' => $this->room->position_x + $this->position_x,
            'y' => $this->room->position_y + $this->position_y,
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOffline($query)
    {
        return $query->where('status', 'offline');
    }

    public function scopeMaintenance($query)
    {
        return $query->where('status', 'maintenance');
    }

    public function setPositionXAttribute($value)
    {
        if ($this->room) {
            $this->attributes['position_x'] = max(0, min($value, $this->room->width));
        } else {
            $this->attributes['position_x'] = max(0, $value);
        }
    }

    public function setPositionYAttribute($value)
    {
        if ($this->room) {
            $this->attributes['position_y'] = max(0, min($value, $this->room->height));
        } else {
            $this->attributes['position_y'] = max(0, $value);
        }
    }
}
