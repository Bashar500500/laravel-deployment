<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Category\CategoryStatus;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\SubCategory\SubCategory;
use App\Models\Course\Course;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Attachment\Attachment;

class Category extends Model
{
    protected $fillable = [
        'name',
        'status',
        'description',
    ];
    protected $casts = [
        'status' => CategoryStatus::class,
    ];

    public function subCategories(): HasMany
    {
        return $this->hasMany(SubCategory::class, 'category_id');
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'category_id');
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
