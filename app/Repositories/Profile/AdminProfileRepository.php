<?php

namespace App\Repositories\Profile;

use App\Repositories\BaseRepository;
use App\Models\Profile\Profile;
use App\DataTransferObjects\Profile\AdminProfileDto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Enums\Attachment\AttachmentReferenceField;
use App\Enums\Attachment\AttachmentType;
use App\Exceptions\CustomException;
use App\Enums\Upload\UploadMessage;

class AdminProfileRepository extends BaseRepository implements AdminProfileRepositoryInterface
{

    public function __construct(Profile $profile)
    {
        parent::__construct($profile);
    }

    public function all(AdminProfileDto $dto): object
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

    public function create(AdminProfileDto $dto, array $data): object
    {
        $profile = DB::transaction(function () use ($dto, $data) {
            $profile = $this->model->create([
                'user_id' => $dto->userId,
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
                $storedFile = Storage::disk('local')->putFileAs('Profile/' . $profile->id . '/Images',
                    $dto->userImage,
                    str()->uuid() . '.' . $dto->userImage->extension());

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

    public function update(AdminProfileDto $dto, int $id, array $data): object
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
                $profile->attachments()->delete();
                Storage::disk('local')->deleteDirectory('Profile/' . $profile->id);

                $storedFile = Storage::disk('local')->putFileAs('Profile/' . $profile->id . '/Images',
                    $dto->userImage,
                    str()->uuid() . '.' . $dto->userImage->extension());

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
            $model->attachments()->delete();
            Storage::disk('local')->deleteDirectory('Profile/' . $model->id);
            return parent::delete($id);
        });

        return (object) $profile;
    }

    public function view(int $id): string
    {
        $model = (object) parent::find($id);

        $file = Storage::disk('local')->path('Profile/' . $id . '/Images/' . $model->attachment->url);

        if (!file_exists($file))
        {
            throw CustomException::notFound('Image');
        }

        return $file;
    }

    public function download(int $id): string
    {
        $model = (object) parent::find($id);

        $file = Storage::disk('local')->path('Profile/' . $id . '/Images/' . $model->attachment->url);

        if (!file_exists($file))
        {
            throw CustomException::notFound('Image');
        }

        return $file;
    }

    public function upload(int $id, array $data): UploadMessage
    {
        $model = (object) parent::find($id);

        DB::transaction(function () use ($data, $model) {
            $exists = Storage::disk('local')->exists('Profile/' . $model->id);

            if ($exists)
            {
                $model->attachments()->delete();
                Storage::disk('local')->deleteDirectory('Profile/' . $model->id);
            }

            $storedFile = Storage::disk('local')->putFileAs('Profile/' . $model->id . '/Images',
                $data['image'],
                basename($data['image']));

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
        $model->attachments()->delete();
        Storage::disk('local')->deleteDirectory('Group/' . $model->id);
    }
}
