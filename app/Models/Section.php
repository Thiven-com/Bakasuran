<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    //

    public function images() {
        return $this->hasMany(SectionImage::class,'section_id');
    }
}
