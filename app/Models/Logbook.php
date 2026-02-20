<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
class Logbook extends Model
{
    protected $table = 'logbook';
    public $timestamps = true;
    protected $fillable = [
        'id_pkl',
        'tgl',
        'kegiatan',
        'status_approve',
        'catatan',
    ];
    protected $casts = [
        'tgl' => 'date',
    ];
    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */
    public function pkl()
    {
        return $this->belongsTo(Pkl::class, 'id_pkl');
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopePending(Builder $query)
    {
        return $query->where('status_approve', 'pending');
    }
    public function scopeApproved(Builder $query)
    {
        return $query->where('status_approve', 'approved');
    }
    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getStatusLabelAttribute()
    {
        return $this->status_approve === 'approved'
            ? 'Disetujui'
            : 'Perlu Revisi';
    }
    public function getStatusColorAttribute()
    {
        return $this->status_approve === 'approved'
            ? 'green'
            : 'red';
    }
}