<?php

namespace App\Models\User;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Profile\Profile;
use App\Models\Message\Message;
use App\Models\Reply\Reply;
use App\Models\Chat\DirectChat;
use App\Models\UserCourseGroup\UserCourseGroup;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Models\Course\Course;
use App\Models\Group\Group;
use App\Models\TeachingHour\TeachingHour;
use App\Models\ScheduleTiming\ScheduleTiming;
use App\Models\Grade\Grade;
use App\Models\Progress\Progress;
use App\Models\Attendance\Attendance;
use App\Models\Email\Email;
use App\Models\Project\Project;
use App\Models\Ticket\Ticket;
use App\Models\Notification\Notification;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guard_name = 'api';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, 'user_id');
    }

    public function directChats(): HasMany
    {
        return $this->hasMany(DirectChat::class, 'user_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'user_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class, 'user_id');
    }

    public function userCourseGroups(): HasMany
    {
        return $this->hasMany(UserCourseGroup::class, 'student_id');
    }

    public function enrolledCourses(): HasManyThrough
    {
        return $this->hasManyThrough(Course::class, UserCourseGroup::class,
            'student_id',
            'id',
            'id',
            'course_id'
        );
    }

    public function ownedCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    public function groups(): HasManyThrough
    {
        return $this->hasManyThrough(Group::class, UserCourseGroup::class,
            'student_id',
            'id',
            'id',
            'group_id'
        );
    }

    public function teachingHours(): HasOne
    {
        return $this->hasOne(TeachingHour::class, 'instructor_id');
    }

    public function scheduleTimings(): HasMany
    {
        return $this->hasMany(ScheduleTiming::class, 'instructor_id');
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class, 'student_id');
    }

    public function progresses(): HasMany
    {
        return $this->hasMany(Progress::class, 'student_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    public function emails(): HasMany
    {
        return $this->hasMany(Email::class, 'user_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'leader_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'instructor_id');
    }

    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'notificationable');
    }

    public function notification(): MorphOne
    {
        return $this->morphOne(Notification::class, 'notificationable');
    }
}
