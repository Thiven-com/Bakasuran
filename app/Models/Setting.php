<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Setting extends Model
{
    use HasFactory;

    use SoftDeletes;
    protected $softDelete = true;

    protected $fillable = [
        'name',
        'key',
        'value',
        'description',
        'status',
        'is_edit',

    ];
    public function shouldRenew(): bool
    {
        return $this->updated_at->lte(Carbon::now()->subDays(5));
    }
}
