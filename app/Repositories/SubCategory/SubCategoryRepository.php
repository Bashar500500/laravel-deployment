<?php

namespace App\Repositories\SubCategory;

use App\Repositories\BaseRepository;
use App\Models\SubCategory\SubCategory;
use App\DataTransferObjects\SubCategory\SubCategoryDto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Enums\Attachment\AttachmentReferenceField;
use App\Enums\Attachment\AttachmentType;
use App\Exceptions\CustomException;
use App\Enums\Upload\UploadMessage;

class SubCategoryRepository extends BaseRepository implements SubCategoryRepositoryInterface
{
    public function __construct(SubCategory $subCategory) {
        parent::__construct($subCategory);
    }

    public function all(SubCategoryDto $dto): object
    {
        return (object) $this->model->with('category', 'attachment')
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
            ->load('category', 'attachment');
    }

    public function create(SubCategoryDto $dto): object
    {
        $subCategory = DB::transaction(function () use ($dto) {
            $subCategory = $this->model->create([
                'category_id' => $dto->categoryId,
                'name' => $dto->name,
                'status' => $dto->status,
                'description' => $dto->description,
            ]);

            if ($dto->subCategoryImage)
            {
                $storedFile = Storage::disk('local')->putFileAs('SubCategory/' . $subCategory->id . '/Images',
                    $dto->subCategoryImage,
                    str()->uuid() . '.' . $dto->subCategoryImage->extension());

                $subCategory->attachment()->create([
                    'reference_field' => AttachmentReferenceField::SubCategoryImage,
                    'type' => AttachmentType::Image,
                    'url' => basename($storedFile),
                ]);
            }

            return $subCategory;
        });

        return (object) $subCategory->load('category', 'attachment');
    }

    public function update(SubCategoryDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $subCategory = DB::transaction(function () use ($dto, $model) {
            $subCategory = tap($model)->update([
                'name' => $dto->name ? $dto->name : $model->name,
                'status' => $dto->status ? $dto->status : $model->status,
                'description' => $dto->description ? $dto->description : $model->description,
            ]);

            if ($dto->subCategoryImage)
            {
                $subCategory->attachments()->delete();
                Storage::disk('local')->deleteDirectory('SubCategory/' . $subCategory->id);

                $storedFile = Storage::disk('local')->putFileAs('SubCategory/' . $subCategory->id . '/Images',
                    $dto->subCategoryImage,
                    str()->uuid() . '.' . $dto->subCategoryImage->extension());

                $subCategory->attachment()->create([
                    'reference_field' => AttachmentReferenceField::SubCategoryImage,
                    'type' => AttachmentType::Image,
                    'url' => basename($storedFile),
                ]);
            }

            return $subCategory;
        });

        return (object) $subCategory->load('category', 'attachment');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $subCategory = DB::transaction(function () use ($id, $model) {
            $model->attachments()->delete();
            Storage::disk('local')->deleteDirectory('SubCategory/' . $model->id);
            return parent::delete($id);
        });

        return (object) $subCategory;
    }

    public function view(int $id): string
    {
        $model = (object) parent::find($id);

        $file = Storage::disk('local')->path('SubCategory/' . $id . '/Images/' . $model->attachment->url);

        if (!file_exists($file))
        {
            throw CustomException::notFound('Image');
        }

        return $file;
    }

    public function download(int $id): string
    {
        $model = (object) parent::find($id);

        $file = Storage::disk('local')->path('SubCategory/' . $id . '/Images/' . $model->attachment->url);

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
            $exists = Storage::disk('local')->exists('SubCategory/' . $model->id);

            if ($exists)
            {
                $model->attachments()->delete();
                Storage::disk('local')->deleteDirectory('SubCategory/' . $model->id);
            }

            $storedFile = Storage::disk('local')->putFileAs('SubCategory/' . $model->id . '/Images',
                $data['image'],
                basename($data['image']));

            array_map('unlink', glob("{$data['finalDir']}/*"));
            rmdir($data['finalDir']);

            $model->attachment()->create([
                'reference_field' => AttachmentReferenceField::SubCategoryImage,
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
        Storage::disk('local')->deleteDirectory('SubCategory/' . $model->id);
    }
}
