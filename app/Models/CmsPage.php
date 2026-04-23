<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsPage extends Model
{
    use HasFactory;


    protected $guarded = [];
    public function category()
    {
        return $this->belongsTo(FooterCategory::class, 'footer_category_id');
    }
}
