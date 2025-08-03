<?php

namespace App\Repositories\Profile;

use App\Repositories\BaseRepository;
use App\Models\Profile\Profile;
use App\DataTransferObjects\Profile\UserProfileDto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Enums\Attachment\AttachmentReferenceField;
use App\Enums\Attachment\AttachmentType;
use App\Exceptions\CustomException;
use App\Enums\Upload\UploadMessage;

class UserProfileRepository extends BaseRepository implements UserProfileRepositoryInterface
{

    public function __construct(Profile $profile)
    {
        parent::__construct($profile);
    }

    public function all(UserProfileDto $dto, array $data): object
    {
        return (object) $this->model->with('user', 'attachment')
            ->latest('created_at')
            ->simplePaginate(
                $dto->pageSize,
                ['*'],
                'page',
                $dto->currentPage,
            );
    }

    public function find(int $id): object
    {
        return (object) parent::find($id)
            ->load('user', 'attachment');
    }

    public function create(UserProfileDto $dto, array $data): object
    {
        $profile = DB::transaction(function () use ($dto, $data) {
            $profile = $this->model->create([
                'user_id' => $data['userId'],
                'date_of_birth' => $dto->dateOfBirth,
                'gender' => $dto->gender,
                'nationality' => $dto->nationality,
                'phone' => $dto->phone,
                'emergency_contact_name' => $dto->emergencyContactName,
                'emergency_contact_relation' => $dto->emergencyContactRelation,
                'emergency_contact_phone' => $dto->emergencyContactPhone,
                'permanent_address' => $data['permanentAddress'],
                'temporary_address' => $data['temporaryAddress'],
                'enrollment_date' => $dto->enrollmentDate,
                'batch' => $dto->batch,
                'current_semester' => $dto->currentSemester,
            ]);

            if ($dto->userImage)
            {
                $storedFile = Storage::disk('supabase')->putFile('Profile/' . $profile->id . '/Images',
                    $dto->userImage);

                $profile->attachment()->create([
                    'reference_field' => AttachmentReferenceField::UserImage,
                    'type' => AttachmentType::Image,
                    'url' => basename($storedFile),
                ]);
            }

            return $profile;
        });

        return (object) $profile->load('user', 'attachment');
    }

    public function update(UserProfileDto $dto, int $id, array $data): object
    {
        $model = (object) parent::find($id);

        $profile = DB::transaction(function () use ($dto, $model, $data) {
            $profile = tap($model)->update([
                'date_of_birth' => $dto->dateOfBirth ? $dto->dateOfBirth : $model->date_of_birth,
                'gender' => $dto->gender ? $dto->gender : $model->gender,
                'nationality' => $dto->nationality ? $dto->nationality : $model->nationality,
                'phone' => $dto->phone ? $dto->phone : $model->phone,
                'emergency_contact_name' => $dto->emergencyContactName ? $dto->emergencyContactName : $model->emergency_contact_name,
                'emergency_contact_relation' => $dto->emergencyContactRelation ? $dto->emergencyContactRelation : $model->emergency_contact_relation,
                'emergency_contact_phone' => $dto->emergencyContactPhone ? $dto->emergencyContactPhone : $model->emergency_contact_phone,
                'permanent_address' => $data['permanentAddress'] ? $data['permanentAddress'] : $model->permanent_address,
                'temporary_address' => $data['temporaryAddress'] ? $data['temporaryAddress'] : $model->temporary_address,
                'enrollment_date' => $dto->enrollmentDate ? $dto->enrollmentDate : $model->enrollment_date,
                'batch' => $dto->batch ? $dto->batch : $model->batch,
                'current_semester' => $dto->currentSemester ? $dto->currentSemester : $model->current_semester,
            ]);

            if ($dto->userImage)
            {
                Storage::disk('supabase')->delete('Profile/' . $profile->id . '/Images/' . $profile->attachment?->url);
                $profile->attachments()->delete();

                $storedFile = Storage::disk('supabase')->putFile('Profile/' . $profile->id . '/Images',
                    $dto->userImage);

                $profile->attachment()->create([
                    'reference_field' => AttachmentReferenceField::UserImage,
                    'type' => AttachmentType::Image,
                    'url' => basename($storedFile),
                ]);
            }

            return $profile;
        });

        return (object) $profile->load('user', 'attachment');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $profile = DB::transaction(function () use ($id, $model) {
            $attachment = $model->attachment;
            Storage::disk('supabase')->delete('Profile/' . $model->id . '/Images/' . $attachment?->url);
            $model->attachment()->delete();
            return parent::delete($id);
        });

        return (object) $profile;
    }

    public function view(int $id): string
    {
        $model = (object) parent::find($id);

        $exists = Storage::disk('supabase')->exists('Profile/' . $model->id . '/Images/' . $model->attachment?->url);

        if (! $exists)
        {
            throw CustomException::notFound('Image');
        }

        $file = Storage::disk('supabase')->get('Profile/' . $model->id . '/Images/' . $model->attachment?->url);
        $tempPath = storage_path('app/private/' . $model->attachment?->url);
        file_put_contents($tempPath, $file);

        return $tempPath;
    }

    public function download(int $id): string
    {
        $model = (object) parent::find($id);

        $exists = Storage::disk('supabase')->exists('Profile/' . $model->id . '/Images/' . $model->attachment?->url);

        if (! $exists)
        {
            throw CustomException::notFound('Image');
        }

        $file = Storage::disk('supabase')->get('Profile/' . $model->id . '/Images/' . $model->attachment?->url);
        $tempPath = storage_path('app/private/' . $model->attachment?->url);
        file_put_contents($tempPath, $file);

        return $tempPath;
    }

    public function upload(int $id, array $data): UploadMessage
    {
        $model = (object) parent::find($id);

        DB::transaction(function () use ($data, $model) {
            Storage::disk('supabase')->delete('Profile/' . $model->id . '/Images/' . $model->attachment?->url);
            $model->attachments()->delete();

            $storedFile = Storage::disk('supabase')->putFile('Profile/' . $model->id . '/Images',
                $data['image']);

            array_map('unlink', glob("{$data['finalDir']}/*"));
            rmdir($data['finalDir']);

            $model->attachment()->create([
                'reference_field' => AttachmentReferenceField::UserImage,
                'type' => AttachmentType::Image,
                'url' => basename($storedFile),
            ]);
        });

        return UploadMessage::Image;
    }

    public function deleteAttachment(int $id): void
    {
        $model = (object) parent::find($id);
        Storage::disk('supabase')->delete('Group/' . $model->id . '/Images/' . $model->attachment?->url);
        $model->attachments()->delete();
    }
}
