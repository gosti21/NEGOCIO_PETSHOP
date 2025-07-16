<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DocumentType extends Model
{
    protected $fillable = [
        'document_type',
        'document_number',
    ];

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }
}
