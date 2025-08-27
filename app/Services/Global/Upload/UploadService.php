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
use App\Enums\LearningActivity\LearningActivityType;
use App\Http\Requests\Upload\File\FileUploadRequest;
use App\Models\Section\Section;
use App\Models\Event\Event;
use App\Models\Category\Category;
use App\Models\SubCategory\SubCategory;
use App\Models\Profile\Profile;
use App\Models\Project\Project;
use App\Models\Assignment\Assignment;
use App\Models\Wiki\Wiki;
use Illuminate\Support\Facades\Auth;

class UploadService
{
    public function __construct(
        protected UploadRepositoryFactory $factory,
    ) {}

    public function uploadCourseImage(ImageUploadRequest $request, Course $course): UploadMessage
    {
        $dto = UploadDto::fromImageUploadRequest($request);

        $dzuuid = str()->uuid();
        $chunkDir = storage_path("app/chunks/{$dzuuid}");
        $extension = $dto->image->getClientOriginalExtension();

        if (!file_exists($chunkDir))
        {
            mkdir($chunkDir, 0777, true);
        }

        $size = $dto->image->getSize();
        $sizeKb = round($size / 1024, 2);
        $dto->image->move($chunkDir, "chunk_{$dto->dzChunkIndex}");

        if (count(scandir($chunkDir)) - 2 == $dto->dzTotalChunkCount)
        {
            $data = $this->mergeChunks(AttachmentType::Image, $dzuuid, $extension, $chunkDir, $dto->dzTotalChunkCount, $sizeKb);

            $repository = $this->factory->make(ModelName::Course);
            return $repository->upload($course->id, $data);
        }

        return UploadMessage::Chunk;
    }

    public function uploadGroupImage(ImageUploadRequest $request, Group $group): UploadMessage
    {
        $dto = UploadDto::fromImageUploadRequest($request);

        $dzuuid = str()->uuid();
        $chunkDir = storage_path("app/chunks/{$dzuuid}");
        $extension = $dto->image->getClientOriginalExtension();

        if (!file_exists($chunkDir))
        {
            mkdir($chunkDir, 0777, true);
        }

        $size = $dto->image->getSize();
        $sizeKb = round($size / 1024, 2);
        $dto->image->move($chunkDir, "chunk_{$dto->dzChunkIndex}");

        if (count(scandir($chunkDir)) - 2 == $dto->dzTotalChunkCount)
        {
            $data = $this->mergeChunks(AttachmentType::Image, $dzuuid, $extension, $chunkDir, $dto->dzTotalChunkCount, $sizeKb);

            $repository = $this->factory->make(ModelName::Group);
            return $repository->upload($group->id, $data);
        }

        return UploadMessage::Chunk;
    }

    public function uploadLearningActivityContent(ContentUploadRequest $request, LearningActivity $learningActivity): UploadMessage
    {
        switch ($learningActivity->type)
        {
            case LearningActivityType::Pdf:
                $dto = UploadDto::fromPdfUploadRequest($request, $learningActivity);
                $type = AttachmentType::Pdf;
                $extension = $dto->pdf->getClientOriginalExtension();
                $file = $dto->pdf;
                break;
            default:
                $dto = UploadDto::fromVideoUploadRequest($request, $learningActivity);
                $type = AttachmentType::Video;
                $extension = $dto->video->getClientOriginalExtension();
                $file = $dto->video;
                break;
        };

        $dzuuid = str()->uuid();
        $chunkDir = storage_path("app/chunks/{$dzuuid}");

        if (!file_exists($chunkDir))
        {
            mkdir($chunkDir, 0777, true);
        }

        $size = $file->getSize();
        $sizeKb = round($size / 1024, 2);
        $file->move($chunkDir, "chunk_{$dto->dzChunkIndex}");

        if (count(scandir($chunkDir)) - 2 == $dto->dzTotalChunkCount)
        {
            $data = $this->mergeChunks($type, $dzuuid, $extension, $chunkDir, $dto->dzTotalChunkCount, $sizeKb);

            $repository = $this->factory->make(ModelName::LearningActivity);
            return $repository->upload($learningActivity->id, $data);
        }

        return UploadMessage::Chunk;
    }

    public function uploadSectionFile(FileUploadRequest $request, Section $section): UploadMessage
    {
        $dto = UploadDto::fromFileUploadRequest($request);

        $dzuuid = str()->uuid();
        $chunkDir = storage_path("app/chunks/{$dzuuid}");
        $extension = $dto->file->getClientOriginalExtension();

        if (!file_exists($chunkDir))
        {
            mkdir($chunkDir, 0777, true);
        }

        $size = $dto->file->getSize();
        $sizeKb = round($size / 1024, 2);
        $dto->file->move($chunkDir, "chunk_{$dto->dzChunkIndex}");

        if (count(scandir($chunkDir)) - 2 == $dto->dzTotalChunkCount)
        {
            $data = $this->mergeChunks(AttachmentType::File, $dzuuid, $extension, $chunkDir, $dto->dzTotalChunkCount, $sizeKb);

            $repository = $this->factory->make(ModelName::Section);
            return $repository->upload($section->id, $data);
        }

        return UploadMessage::Chunk;
    }

    public function uploadEventFile(FileUploadRequest $request, Event $event): UploadMessage
    {
        $dto = UploadDto::fromFileUploadRequest($request);

        $dzuuid = str()->uuid();
        $chunkDir = storage_path("app/chunks/{$dzuuid}");
        $extension = $dto->file->getClientOriginalExtension();

        if (!file_exists($chunkDir))
        {
            mkdir($chunkDir, 0777, true);
        }

        $size = $dto->file->getSize();
        $sizeKb = round($size / 1024, 2);
        $dto->file->move($chunkDir, "chunk_{$dto->dzChunkIndex}");

        if (count(scandir($chunkDir)) - 2 == $dto->dzTotalChunkCount)
        {
            $data = $this->mergeChunks(AttachmentType::File, $dzuuid, $extension, $chunkDir, $dto->dzTotalChunkCount, $sizeKb);

            $repository = $this->factory->make(ModelName::Event);
            return $repository->upload($event->id, $data);
        }

        return UploadMessage::Chunk;
    }

    public function uploadCategoryImage(ImageUploadRequest $request, Category $category): UploadMessage
    {
        $dto = UploadDto::fromImageUploadRequest($request);

        $dzuuid = str()->uuid();
        $chunkDir = storage_path("app/chunks/{$dzuuid}");
        $extension = $dto->image->getClientOriginalExtension();

        if (!file_exists($chunkDir))
        {
            mkdir($chunkDir, 0777, true);
        }

        $size = $dto->image->getSize();
        $sizeKb = round($size / 1024, 2);
        $dto->image->move($chunkDir, "chunk_{$dto->dzChunkIndex}");

        if (count(scandir($chunkDir)) - 2 == $dto->dzTotalChunkCount)
        {
            $data = $this->mergeChunks(AttachmentType::Image, $dzuuid, $extension, $chunkDir, $dto->dzTotalChunkCount, $sizeKb);

            $repository = $this->factory->make(ModelName::Category);
            return $repository->upload($category->id, $data);
        }

        return UploadMessage::Chunk;
    }

    public function uploadSubCategoryImage(ImageUploadRequest $request, SubCategory $subCategory): UploadMessage
    {
        $dto = UploadDto::fromImageUploadRequest($request);

        $dzuuid = str()->uuid();
        $chunkDir = storage_path("app/chunks/{$dzuuid}");
        $extension = $dto->image->getClientOriginalExtension();

        if (!file_exists($chunkDir))
        {
            mkdir($chunkDir, 0777, true);
        }

        $size = $dto->image->getSize();
        $sizeKb = round($size / 1024, 2);
        $dto->image->move($chunkDir, "chunk_{$dto->dzChunkIndex}");

        if (count(scandir($chunkDir)) - 2 == $dto->dzTotalChunkCount)
        {
            $data = $this->mergeChunks(AttachmentType::Image, $dzuuid, $extension, $chunkDir, $dto->dzTotalChunkCount, $sizeKb);

            $repository = $this->factory->make(ModelName::SubCategory);
            return $repository->upload($subCategory->id, $data);
        }

        return UploadMessage::Chunk;
    }

    public function uploadUserProfileImage(ImageUploadRequest $request): UploadMessage
    {
        $dto = UploadDto::fromImageUploadRequest($request);

        $dzuuid = str()->uuid();
        $chunkDir = storage_path("app/chunks/{$dzuuid}");
        $extension = $dto->image->getClientOriginalExtension();

        if (!file_exists($chunkDir))
        {
            mkdir($chunkDir, 0777, true);
        }

        $size = $dto->image->getSize();
        $sizeKb = round($size / 1024, 2);
        $dto->image->move($chunkDir, "chunk_{$dto->dzChunkIndex}");

        if (count(scandir($chunkDir)) - 2 == $dto->dzTotalChunkCount)
        {
            $data = $this->mergeChunks(AttachmentType::Image, $dzuuid, $extension, $chunkDir, $dto->dzTotalChunkCount, $sizeKb);

            $repository = $this->factory->make(ModelName::UserProfile);
            return $repository->upload(Auth::user()->profile->id, $data);
        }

        return UploadMessage::Chunk;
    }

    public function uploadAdminProfileImage(ImageUploadRequest $request, Profile $profile): UploadMessage
    {
        $dto = UploadDto::fromImageUploadRequest($request);

        $dzuuid = str()->uuid();
        $chunkDir = storage_path("app/chunks/{$dzuuid}");
        $extension = $dto->image->getClientOriginalExtension();

        if (!file_exists($chunkDir))
        {
            mkdir($chunkDir, 0777, true);
        }

        $size = $dto->image->getSize();
        $sizeKb = round($size / 1024, 2);
        $dto->image->move($chunkDir, "chunk_{$dto->dzChunkIndex}");

        if (count(scandir($chunkDir)) - 2 == $dto->dzTotalChunkCount)
        {
            $data = $this->mergeChunks(AttachmentType::Image, $dzuuid, $extension, $chunkDir, $dto->dzTotalChunkCount, $sizeKb);

            $repository = $this->factory->make(ModelName::AdminProfile);
            return $repository->upload($profile->id, $data);
        }

        return UploadMessage::Chunk;
    }

    public function uploadProjectFile(FileUploadRequest $request, Project $project): UploadMessage
    {
        $dto = UploadDto::fromFileUploadRequest($request);

        $dzuuid = str()->uuid();
        $chunkDir = storage_path("app/chunks/{$dzuuid}");
        $extension = $dto->file->getClientOriginalExtension();

        if (!file_exists($chunkDir))
        {
            mkdir($chunkDir, 0777, true);
        }

        $size = $dto->file->getSize();
        $sizeKb = round($size / 1024, 2);
        $dto->file->move($chunkDir, "chunk_{$dto->dzChunkIndex}");

        if (count(scandir($chunkDir)) - 2 == $dto->dzTotalChunkCount)
        {
            $data = $this->mergeChunks(AttachmentType::File, $dzuuid, $extension, $chunkDir, $dto->dzTotalChunkCount, $sizeKb);

            $repository = $this->factory->make(ModelName::Project);
            return $repository->upload($project->id, $data);
        }

        return UploadMessage::Chunk;
    }

    public function uploadAssignmentFile(FileUploadRequest $request, Assignment $assignment): UploadMessage
    {
        $dto = UploadDto::fromFileUploadRequest($request);

        $dzuuid = str()->uuid();
        $chunkDir = storage_path("app/chunks/{$dzuuid}");
        $extension = $dto->file->getClientOriginalExtension();

        if (!file_exists($chunkDir))
        {
            mkdir($chunkDir, 0777, true);
        }

        $size = $dto->file->getSize();
        $sizeKb = round($size / 1024, 2);
        $dto->file->move($chunkDir, "chunk_{$dto->dzChunkIndex}");

        if (count(scandir($chunkDir)) - 2 == $dto->dzTotalChunkCount)
        {
            $data = $this->mergeChunks(AttachmentType::File, $dzuuid, $extension, $chunkDir, $dto->dzTotalChunkCount, $sizeKb);

            $repository = $this->factory->make(ModelName::Assignment);
            return $repository->upload($assignment->id, $data);
        }

        return UploadMessage::Chunk;
    }

    public function uploadWikiFile(FileUploadRequest $request, Wiki $wiki): UploadMessage
    {
        $dto = UploadDto::fromFileUploadRequest($request);

        $dzuuid = str()->uuid();
        $chunkDir = storage_path("app/chunks/{$dzuuid}");
        $extension = $dto->file->getClientOriginalExtension();

        if (!file_exists($chunkDir))
        {
            mkdir($chunkDir, 0777, true);
        }

        $size = $dto->file->getSize();
        $sizeKb = round($size / 1024, 2);
        $dto->file->move($chunkDir, "chunk_{$dto->dzChunkIndex}");

        if (count(scandir($chunkDir)) - 2 == $dto->dzTotalChunkCount)
        {
            $data = $this->mergeChunks(AttachmentType::File, $dzuuid, $extension, $chunkDir, $dto->dzTotalChunkCount, $sizeKb);

            $repository = $this->factory->make(ModelName::Wiki);
            return $repository->upload($wiki->id, $data);
        }

        return UploadMessage::Chunk;
    }

    private function mergeChunks(
        AttachmentType $type,
        string $uuid,
        string $extension,
        string $chunkDir,
        int $dzTotalChunkCount,
        float $sizeKb): array
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

        $data = $this->prepareReturnData($type, $finalPath, $finalDir, $sizeKb);

        return $data;
    }

    private function prepareReturnData(AttachmentType $type, string $file, string $finalDir, float $sizeKb): array
    {
        return match ($type) {
            AttachmentType::Image => [
                'image' => $file,
                'finalDir' => $finalDir,
                'sizeKb' => $sizeKb,
            ],
            AttachmentType::Pdf => [
                'pdf' => $file,
                'finalDir' => $finalDir,
                'sizeKb' => $sizeKb,
            ],
            AttachmentType::Video => [
                'video' => $file,
                'finalDir' => $finalDir,
                'sizeKb' => $sizeKb,
            ],
            AttachmentType::File => [
                'file' => $file,
                'finalDir' => $finalDir,
                'sizeKb' => $sizeKb,
            ],
        };
    }
}
