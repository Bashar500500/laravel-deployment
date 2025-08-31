<?php

namespace App\Repositories\Assignment;

use App\Repositories\BaseRepository;
use App\Models\Assignment\Assignment;
use App\DataTransferObjects\Assignment\AssignmentDto;
use Illuminate\Support\Facades\DB;
use App\DataTransferObjects\Assignment\AssignmentSubmitDto;
use App\Enums\Assessment\AssessmentType;
use App\Enums\AssignmentSubmit\AssignmentSubmitStatus;
use Illuminate\Support\Facades\Storage;
use App\Enums\Attachment\AttachmentReferenceField;
use App\Enums\Attachment\AttachmentType;
use App\Enums\Model\ModelTypePath;
use App\Enums\Challenge\ChallengeStatus;
use App\Enums\Challenge\ChallengeType;
use Illuminate\Support\Carbon;
use App\Models\Rule\Rule;
use App\Models\Badge\Badge;
use App\Enums\UserAward\UserAwardType;
use App\Enums\Trait\ModelName;
use App\Enums\Exception\ForbiddenExceptionMessage;
use App\Enums\Grade\GradeCategory;
use App\Enums\Grade\GradeResubmission;
use App\Enums\Grade\GradeStatus;
use App\Enums\Grade\GradeTrend;
use App\Enums\Plagiarism\PlagiarismStatus;
use App\Enums\Rubric\RubricType;
use App\Exceptions\CustomException;
use ZipArchive;
use Illuminate\Support\Facades\File;
use App\Enums\Upload\UploadMessage;
use App\Models\Rubric\Rubric;

class InstructorAssignmentRepository extends BaseRepository implements AssignmentRepositoryInterface
{
    public function __construct(Assignment $assignment) {
        parent::__construct($assignment);
    }

    public function all(AssignmentDto $dto): object
    {
        return (object) $this->model->where('course_id', $dto->courseId)
            ->with('course', 'assignmentSubmits', 'grades', 'attachments')
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
            ->load('course', 'assignmentSubmits', 'grades', 'attachments');
    }

    public function create(AssignmentDto $dto): object
    {
        $rubric = Rubric::find($dto->rubricId);

        $isPeerReviewed = $dto->peerReviewSettings['is_peer_reviewed'];
        if (($isPeerReviewed && $rubric->type != RubricType::PeerReview) ||
            (! $isPeerReviewed && $rubric->type != RubricType::Assignment))
        {
            throw CustomException::forbidden(ModelName::Assignment, ForbiddenExceptionMessage::AssignmentRubric);
        }

        $assignment = DB::transaction(function () use ($dto) {
            $assignment = (object) $this->model->create([
                'course_id' => $dto->courseId,
                'rubric_id' => $dto->rubricId,
                'title' => $dto->title,
                'status' => $dto->status,
                'description' => $dto->description,
                'instructions' => $dto->instructions,
                'due_date' => $dto->dueDate,
                'points' => $dto->points,
                'peer_review_settings' => $dto->peerReviewSettings,
                'submission_settings' => $dto->submissionSettings,
                'policies' => $dto->policies,
            ]);

            if ($dto->files)
            {
                foreach ($dto->files as $file)
                {
                    $storedFile = Storage::disk('supabase')->putFile('Assignment/' . $assignment->id . '/Files',
                        $file);

                    $size = $file->getSize();
                    $sizeKb = round($size / 1024, 2);

                    $assignment->attachment()->create([
                        'reference_field' => AttachmentReferenceField::AssignmentFiles,
                        'type' => AttachmentType::File,
                        'url' => basename($storedFile),
                        'size_kb' => $sizeKb,
                    ]);
                }
            }

            $students = $assignment->course->students;
            foreach ($students as $student)
            {
                $assignment->grade()->create([
                    'student_id' => $student->student_id,
                    'due_date' => $assignment->due_date,
                    'status' => GradeStatus::Missing,
                    'points_earned' => 0,
                    'max_points' => $assignment->points,
                    'percentage' => 0,
                    'category' => $assignment->type == AssessmentType::Quiz ? GradeCategory::Quiz : GradeCategory::Exam,
                    'class_average' => 0,
                    'trend' => GradeTrend::Neutral,
                    'trend_data' => [],
                    'resubmission' => GradeResubmission::Available,
                    'resubmission_due' => $assignment->policies['late_submission']['cutoff_date'],
                ]);
            }

            return $assignment;
        });

        return (object) $assignment->load('course', 'assignmentSubmits', 'grades', 'attachments');
    }

    public function update(AssignmentDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        if ($dto->rubricId)
        {
            $rubric = Rubric::find($dto->rubricId);

            $isPeerReviewed = $dto->peerReviewSettings['is_peer_reviewed'];
            if (($isPeerReviewed && $rubric->type != RubricType::PeerReview) ||
                (! $isPeerReviewed && $rubric->type != RubricType::Assignment))
            {
                throw CustomException::forbidden(ModelName::Assignment, ForbiddenExceptionMessage::AssignmentRubric);
            }
        }

        $assignment = DB::transaction(function () use ($dto, $model) {
            $assignment = tap($model)->update([
                'rubric_id' => $dto->rubricId ? $dto->rubricId : $model->rubric_id,
                'title' => $dto->title ? $dto->title : $model->title,
                'status' => $dto->status ? $dto->status : $model->status,
                'description' => $dto->description ? $dto->description : $model->description,
                'instructions' => $dto->instructions ? $dto->instructions : $model->instructions,
                'due_date' => $dto->dueDate ? $dto->dueDate : $model->due_date,
                'points' => $dto->points ? $dto->points : $model->points,
                'peer_review_settings' => $dto->peerReviewSettings ? $dto->peerReviewSettings : $model->peer_review_settings,
                'submission_settings' => $dto->submissionSettings ? $dto->submissionSettings : $model->submission_settings,
                'policies' => $dto->policies ? $dto->policies : $model->policies,
            ]);

            if ($dto->files)
            {
                $attachments = $assignment->attachments;
                foreach ($attachments as $attachment)
                {
                    Storage::disk('supabase')->delete('Assignment/' . $assignment->id . '/Files/' . $attachment?->url);
                }
                $assignment->attachments()->delete();

                foreach ($dto->files as $file)
                {
                    $storedFile = Storage::disk('supabase')->putFile('Assignment/' . $assignment->id . '/Files',
                        $file);

                    $size = $file->getSize();
                    $sizeKb = round($size / 1024, 2);

                    $assignment->attachment()->create([
                        'reference_field' => AttachmentReferenceField::AssignmentFiles,
                        'type' => AttachmentType::File,
                        'url' => basename($storedFile),
                        'size_kb' => $sizeKb,
                    ]);
                }
            }

            return $assignment;
        });

        return (object) $assignment->load('course', 'assignmentSubmits', 'grades', 'attachments');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $assignment = DB::transaction(function () use ($id, $model) {
            $assignmentSubmits = $model->assignmentSubmits;

            foreach ($assignmentSubmits as $assignmentSubmit)
            {
                $attachments = $assignmentSubmit->attachments;
                foreach ($attachments as $attachment)
                {
                    $reference_field = $attachment->reference_field;
                    switch ($reference_field)
                    {
                        case AttachmentReferenceField::AssignmentSubmitInstructorFiles:
                            Storage::disk('supabase')->delete('AssignmentSubmit/' . $model->id . '/Files/' . $model->student_id . '/Instructor/' . $attachment?->url);
                            break;
                        default:
                            Storage::disk('supabase')->delete('AssignmentSubmit/' . $model->id . '/Files/' . $model->student_id . '/Student/' . $attachment?->url);
                            break;
                    }
                }
                $assignmentSubmit->attachments()->delete();
            }

            $attachments = $model->attachments;
            foreach ($attachments as $attachment)
            {
                Storage::disk('supabase')->delete('Assignment/' . $model->id . '/Files/' . $attachment?->url);
            }
            $model->attachments()->delete();

            return parent::delete($id);
        });

        return (object) $assignment;
    }

    public function view(int $id, string $fileName): string
    {
        $model = (object) parent::find($id);

        $exists = Storage::disk('supabase')->exists('Assignment/' . $model->id . '/Files/' . $fileName);

        if (! $exists)
        {
            throw CustomException::notFound('File');
        }

        $file = Storage::disk('supabase')->get('Assignment/' . $model->id . '/Files/' . $fileName);
        $tempPath = storage_path('app/private/' . $fileName);
        file_put_contents($tempPath, $file);

        return $tempPath;
    }

    public function download(int $id): string
    {
        $model = (object) parent::find($id);
        $attachments = $model->attachments;

        if (count($attachments) == 0)
        {
            throw CustomException::notFound('Files');
        }

        $zip = new ZipArchive();
        $zipName = 'Assignment-Files.zip';
        $zipPath = storage_path('app/private/' . $zipName);
        $tempFiles = [];

        if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
            foreach ($attachments as $attachment) {
                $file = Storage::disk('supabase')->get('Assignment/' . $model->id . '/Files/' . $attachment?->url);
                $tempPath = storage_path('app/private/' . $attachment?->url);
                file_put_contents($tempPath, $file);
                $zip->addFromString(basename($tempPath), file_get_contents($tempPath));
                $tempFiles[] = $tempPath;
            }
            $zip->close();
            File::delete($tempFiles);
        }

        return $zipPath;
    }

    public function upload(int $id, array $data): UploadMessage
    {
        $model = (object) parent::find($id);

        DB::transaction(function () use ($data, $model) {
            $storedFile = Storage::disk('supabase')->putFile('Assignment/' . $model->id . '/Files',
                $data['file']);

            array_map('unlink', glob("{$data['finalDir']}/*"));
            rmdir($data['finalDir']);

            $model->attachment()->create([
                'reference_field' => AttachmentReferenceField::AssignmentFiles,
                'type' => AttachmentType::File,
                'url' => basename($storedFile),
                'size_kb' => $data['sizeKb'],
            ]);
        });

        return UploadMessage::File;
    }

    public function deleteAttachment(int $id, string $fileName): void
    {
        $model = (object) parent::find($id);

        $exists = Storage::disk('supabase')->exists('Assignment/' . $model->id . '/Files/' . $fileName);

        if (! $exists)
        {
            throw CustomException::notFound('File');
        }

        $attachment = $model->attachments()->where('url', $fileName)->first();
        Storage::disk('supabase')->delete('Assignment/' . $model->id . '/Files/' . $attachment?->url);
        $model->attachments()->where('url', $fileName)->delete();
    }

    public function submit(AssignmentSubmitDto $dto, array $data): object
    {
        return (object) [];
    }
}
