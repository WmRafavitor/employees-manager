<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'date_birth', 'role', 'document', 'hired_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'hired_at' => 'datetime',
        'date_birth' => 'datetime',
    ];

    /**
     * Set the employees's document.
     *
     * @param  string  $value
     * @return void
     */
    public function setDocumentAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['document'] = null;
            return;
        }
        $this->attributes['document'] = preg_replace('/[^0-9]/', '', $value);
    }

    /**
     * Get the employee's document.
     *
     * @param  string  $value
     * @return string
     */
    public function getDocumentAttribute($value)
    {
        $cpf = preg_replace("/\D/", '', $value);

        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cpf);
    }
}
