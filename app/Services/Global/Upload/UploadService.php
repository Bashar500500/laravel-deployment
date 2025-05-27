<?php

namespace App\Services\Global\Upload;

use App\Factories\Upload\UploadRepositoryFactory;
use App\Http\Requests\Upload\Image\ImageUploadRequest;
use App\Models\Course\Course;
use App\DataTransferObjects\Upload\UploadDto;
use App\Enums\Attachment\AttachmentType;
use App\Enums\Trait\ModelName;
use App\Enums\Upload\UploadMessage;
use App\Models\Group\Group;
use App\Http\Requests\Upload\Content\ContentUploadRequest;
use App\Models\LearningActivity\LearningActivity;
use App\Enums\LearningActivity\LearningActivityContentType;
use App\Http\Requests\Upload\File\FileUploadRequest;
use App\Models\Section\Section;
use App\Models\Event\Event;
use App\Models\Category\Category;
use App\Models\Profile\Profile;
use App\Models\SubCategory\SubCategory;
use Illuminate\Support\Facades\Auth;

class UploadService
{
    public function __construct(
        protected UploadRepositoryFactory $factory,
    ) {}

    public function uploadCourseImage(ImageUploadRequest $request, Course $course): UploadMessage
    {
        $dto = UploadDto::fromImageUploadRequest($request);

        $chunkDir = storage_path("app/chunks/{$dto->dzuuid}");
        $extension = $dto->image->extension();

        if (!file_exists($chunkDir))
        {
            mkdir($chunkDir, 0777, true);
        }

        $dto->image->move($chunkDir, "chunk_{$dto->dzChunkIndex}");

        if (count(scandir($chunkDir)) - 2 == $dto->dzTotalChunkCount)
        {
            $data = $this->mergeChunks(AttachmentType::Image, $dto->dzuuid, $extension, $chunkDir, $dto->dzTotalChunkCount);

            $repository = $this->factory->make(ModelName::Course);
            return $repository->upload($course->id, $data);
        }

        return UploadMessage::Chunk;
    }

    public function uploadGroupImage(ImageUploadRequest $request, Group $group): UploadMessage
    {
        $dto = UploadDto::fromImageUploadRequest($request);

        $chunkDir = storage_path("app/chunks/{$dto->dzuuid}");
        $extension = $dto->image->extension();

        if (!file_exists($chunkDir))
        {
            mkdir($chunkDir, 0777, true);
        }

        $dto->image->move($chunkDir, "chunk_{$dto->dzChunkIndex}");

        if (count(scandir($chunkDir)) - 2 == $dto->dzTotalChunkCount)
        {
            $data = $this->mergeChunks(AttachmentType::Image, $dto->dzuuid, $extension, $chunkDir, $dto->dzTotalChunkCount);

            $repository = $this->factory->make(ModelName::Group);
            return $repository->upload($group->id, $data);
        }

        return UploadMessage::Chunk;
    }

    public function uploadLearningActivityContent(ContentUploadRequest $request, LearningActivity $learningActivity): UploadMessage
    {
        switch ($learningActivity->content_type)
        {
            case LearningActivityContentType::Pdf:
                $dto = UploadDto::fromPdfUploadRequest($request, $learningActivity);
                $type = AttachmentType::Pdf;
                $extension = $dto->pdf->extension();
                $file = $dto->pdf;
                break;
            default:
                $dto = UploadDto::fromVideoUploadRequest($request, $learningActivity);
                $type = AttachmentType::Video;
                $extension = $dto->video->extension();
                $file = $dto->video;
                break;
        };

        $chunkDir = storage_path("app/chunks/{$dto->dzuuid}");

        if (!file_exists($chunkDir))
        {
            mkdir($chunkDir, 0777, true);
        }

        $file->move($chunkDir, "chunk_{$dto->dzChunkIndex}");

        if (count(scandir($chunkDir)) - 2 == $dto->dzTotalChunkCount)
        {
            $data = $this->mergeChunks($type, $dto->dzuuid, $extension, $chunkDir, $dto->dzTotalChunkCount);

            $repository = $this->factory->make(ModelName::LearningActivity);
            return $repository->upload($learningActivity->id, $data);
        }

        return UploadMessage::Chunk;
    }

    public function uploadSectionFile(FileUploadRequest $request, Section $section): UploadMessage
    {
        $dto = UploadDto::fromFileUploadRequest($request);

        $chunkDir = storage_path("app/chunks/{$dto->dzuuid}");
        $extension = is_null($dto->file->extension()) ? 'txt' : $dto->file->extension();

        if (!file_exists($chunkDir))
        {
            mkdir($chunkDir, 0777, true);
        }

        $dto->file->move($chunkDir, "chunk_{$dto->dzChunkIndex}");

        if (count(scandir($chunkDir)) - 2 == $dto->dzTotalChunkCount)
        {
            $data = $this->mergeChunks(AttachmentType::File, $dto->dzuuid, $extension, $chunkDir, $dto->dzTotalChunkCount);

            $repository = $this->factory->make(ModelName::Section);
            return $repository->upload($section->id, $data);
        }

        return UploadMessage::Chunk;
    }

    public function uploadEventFile(FileUploadRequest $request, Event $event): UploadMessage
    {
        $dto = UploadDto::fromFileUploadRequest($request);

        $chunkDir = storage_path("app/chunks/{$dto->dzuuid}");
        $extension = is_null($dto->file->extension()) ? 'txt' : $dto->file->extension();

        if (!file_exists($chunkDir))
        {
            mkdir($chunkDir, 0777, true);
        }

        $dto->file->move($chunkDir, "chunk_{$dto->dzChunkIndex}");

        if (count(scandir($chunkDir)) - 2 == $dto->dzTotalChunkCount)
        {
            $data = $this->mergeChunks(AttachmentType::File, $dto->dzuuid, $extension, $chunkDir, $dto->dzTotalChunkCount);

            $repository = $this->factory->make(ModelName::Event);
            return $repository->upload($event->id, $data);
        }

        return UploadMessage::Chunk;
    }

    public function uploadCategoryImage(ImageUploadRequest $request, Category $category): UploadMessage
    {
        $dto = UploadDto::fromImageUploadRequest($request);

        $chunkDir = storage_path("app/chunks/{$dto->dzuuid}");
        $extension = $dto->image->extension();

        if (!file_exists($chunkDir))
        {
            mkdir($chunkDir, 0777, true);
        }

        $dto->image->move($chunkDir, "chunk_{$dto->dzChunkIndex}");

        if (count(scandir($chunkDir)) - 2 == $dto->dzTotalChunkCount)
        {
            $data = $this->mergeChunks(AttachmentType::Image, $dto->dzuuid, $extension, $chunkDir, $dto->dzTotalChunkCount);

            $repository = $this->factory->make(ModelName::Category);
            return $repository->upload($category->id, $data);
        }

        return UploadMessage::Chunk;
    }

    public function uploadSubCategoryImage(ImageUploadRequest $request, SubCategory $subCategory): UploadMessage
    {
        $dto = UploadDto::fromImageUploadRequest($request);

        $chunkDir = storage_path("app/chunks/{$dto->dzuuid}");
        $extension = $dto->image->extension();

        if (!file_exists($chunkDir))
        {
            mkdir($chunkDir, 0777, true);
        }

        $dto->image->move($chunkDir, "chunk_{$dto->dzChunkIndex}");

        if (count(scandir($chunkDir)) - 2 == $dto->dzTotalChunkCount)
        {
            $data = $this->mergeChunks(AttachmentType::Image, $dto->dzuuid, $extension, $chunkDir, $dto->dzTotalChunkCount);

            $repository = $this->factory->make(ModelName::SubCategory);
            return $repository->upload($subCategory->id, $data);
        }

        return UploadMessage::Chunk;
    }

    public function uploadUserProfileImage(ImageUploadRequest $request): UploadMessage
    {
        $dto = UploadDto::fromImageUploadRequest($request);

        $chunkDir = storage_path("app/chunks/{$dto->dzuuid}");
        $extension = $dto->image->extension();

        if (!file_exists($chunkDir))
        {
            mkdir($chunkDir, 0777, true);
        }

        $dto->image->move($chunkDir, "chunk_{$dto->dzChunkIndex}");

        if (count(scandir($chunkDir)) - 2 == $dto->dzTotalChunkCount)
        {
            $data = $this->mergeChunks(AttachmentType::Image, $dto->dzuuid, $extension, $chunkDir, $dto->dzTotalChunkCount);

            $repository = $this->factory->make(ModelName::UserProfile);
            return $repository->upload(Auth::user()->profile->id, $data);
        }

        return UploadMessage::Chunk;
    }

    public function uploadAdminProfileImage(ImageUploadRequest $request, Profile $profile): UploadMessage
    {
        $dto = UploadDto::fromImageUploadRequest($request);

        $chunkDir = storage_path("app/chunks/{$dto->dzuuid}");
        $extension = $dto->image->extension();

        if (!file_exists($chunkDir))
        {
            mkdir($chunkDir, 0777, true);
        }

        $dto->image->move($chunkDir, "chunk_{$dto->dzChunkIndex}");

        if (count(scandir($chunkDir)) - 2 == $dto->dzTotalChunkCount)
        {
            $data = $this->mergeChunks(AttachmentType::Image, $dto->dzuuid, $extension, $chunkDir, $dto->dzTotalChunkCount);

            $repository = $this->factory->make(ModelName::AdminProfile);
            return $repository->upload($profile->id, $data);
        }

        return UploadMessage::Chunk;
    }

    private function mergeChunks(
        AttachmentType $type,
        string $uuid,
        string $extension,
        string $chunkDir,
        int $dzTotalChunkCount): array
    {
        $finalDir = storage_path("app/uploads/{$uuid}");

        if (!file_exists($finalDir))
        {
            mkdir($finalDir, 0777, true);
        }

        $finalPath = $finalDir . "/{$uuid}.{$extension}";
        $output = fopen($finalPath, 'wb');

        for ($i = 0; $i < $dzTotalChunkCount; $i++)
        {
            $chunkFile = "{$chunkDir}/chunk_{$i}";

            if (file_exists($chunkFile))
            {
                fwrite($output, file_get_contents($chunkFile));
            }
        }

        fclose($output);
        array_map('unlink', glob("{$chunkDir}/*"));
        rmdir($chunkDir);

        $data = $this->prepareReturnData($type, $finalPath, $finalDir);

        return $data;
    }

    private function prepareReturnData(AttachmentType $type, string $file, string $finalDir): array
    {
        return match ($type) {
            AttachmentType::Image => [
                'image' => $file,
                'finalDir' => $finalDir,
            ],
            AttachmentType::Pdf => [
                'pdf' => $file,
                'finalDir' => $finalDir,
            ],
            AttachmentType::Video => [
                'video' => $file,
                'finalDir' => $finalDir,
            ],
            AttachmentType::File => [
                'file' => $file,
                'finalDir' => $finalDir,
            ],
        };
    }
}
