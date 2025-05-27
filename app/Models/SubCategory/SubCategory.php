<?php

namespace App\Models\SubCategory;

use Illuminate\Database\Eloquent\Model;
use App\Enums\SubCategory\SubCategoryStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Category\Category;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Attachment\Attachment;

class SubCategory extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'status',
        'description',
    ];

    protected $casts = [
        'status' => SubCategoryStatus::class,
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }

    public function attachment(): MorphOne
    {
        return $this->morphOne(Attachment::class, 'attachmentable');
    }
}
