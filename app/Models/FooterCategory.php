<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterCategory extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public function cmsPages()
    {
        return $this->hasMany(CmsPage::class)->orderBy('sort_order');
    }
}
