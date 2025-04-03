<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Timeline extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'start_date',
        'start_date_actual',
        'end_date_actual',
        'order',
        'is_enabled',
        'is_active' // Tambahkan is_active ke fillable
    ];

    protected $casts = [
        'start_date_actual' => 'date',
        'end_date_actual' => 'date',
        'is_enabled' => 'boolean',
        'is_active' => 'boolean', // Tambahkan is_active ke casts
        'order' => 'integer',
    ];

    /**
     * Check if this timeline event is automatically active based on dates
     *
     * @return bool
     */
    public function isDateActive()
    {
        // If no dates are set, return false
        if (!$this->start_date_actual && !$this->end_date_actual) {
            return false;
        }

        $today = Carbon::now();

        // If only start date is set
        if ($this->start_date_actual && !$this->end_date_actual) {
            return $today->gte($this->start_date_actual);
        }

        // If only end date is set
        if (!$this->start_date_actual && $this->end_date_actual) {
            return $today->lte($this->end_date_actual);
        }

        // If both dates are set
        return $today->gte($this->start_date_actual) && $today->lte($this->end_date_actual);
    }

    /**
     * Getter for the timeline active state
     * Combines manual is_active flag and date-based activity
     *
     * @return bool
     */
    public function getIsActiveAttribute($value)
    {
        // Jika is_active sudah diset true secara manual, gunakan itu
        if ($value) {
            return true;
        }

        // Jika tidak, cek date-based activity
        return $this->isDateActive();
    }

    /**
     * Format the dates for display
     *
     * @return string
     */
    public function getFormattedDateAttribute()
    {
        // If a custom date format is provided, use that
        if ($this->start_date) {
            return $this->start_date;
        }

        // Otherwise generate from actual dates
        if ($this->start_date_actual && $this->end_date_actual) {
            $start = Carbon::parse($this->start_date_actual)->format('j F Y');
            $end = Carbon::parse($this->end_date_actual)->format('j F Y');
            return "$start - $end";
        }

        if ($this->start_date_actual) {
            return Carbon::parse($this->start_date_actual)->format('j F Y');
        }

        if ($this->end_date_actual) {
            return Carbon::parse($this->end_date_actual)->format('j F Y');
        }

        return 'Tanggal belum ditentukan';
    }
}
