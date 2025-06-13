<?php

namespace App\Repositories\Question;

use App\Repositories\BaseRepository;
use App\Models\Question\Question;
use App\DataTransferObjects\Question\QuestionDto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Enums\Attachment\AttachmentReferenceField;
use App\Enums\Attachment\AttachmentType;
use App\Exceptions\CustomException;
use App\Enums\Upload\UploadMessage;

class QuestionRepository extends BaseRepository implements QuestionRepositoryInterface
{
    public function __construct(Question $question) {
        parent::__construct($question);
    }

    public function all(QuestionDto $dto): object
    {
        return (object) $this->model->where('course_id', $dto->courseId)
            ->with('course', 'attachments')
            ->latest('created_at')
            ->simplePaginate(
                $dto->pageSize,
                ['*'],
                'page',
                $dto->currentPage,
            );
    }

    public function allWithFilter(QuestionDto $dto): object
    {
        return (object) $this->model->where('course_id', $dto->courseId)
            ->where('category', $dto->category)
            ->with('attachment', 'students')
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
            ->load('course', 'attachments');
    }

    public function create(QuestionDto $dto): object
    {
        $question = DB::transaction(function () use ($dto) {
            $question = $this->model->create([
                'course_id' => $dto->courseId,
                'category' => $dto->category,
                'question' => $dto->question,
                'option_a' => $dto->optionA,
                'option_b' => $dto->optionB,
                'option_c' => $dto->optionC,
                'option_d' => $dto->optionD,
                'correct_answer' => $dto->correctAnswer,
                'code_snippets' => $dto->codeSnippets,
                'answer_explanation' => $dto->answerExplanation,
            ]);

            if ($dto->questionImage)
            {
                $storedFile = Storage::disk('local')->putFileAs('Question/' . $question->id . '/Images',
                    $dto->questionImage,
                    str()->uuid() . '.' . $dto->questionImage->extension());

                $question->attachment()->create([
                    'reference_field' => AttachmentReferenceField::QuestionImage,
                    'type' => AttachmentType::Image,
                    'url' => basename($storedFile),
                ]);
            }

            $question->attachment()->create([
                'reference_field' => AttachmentReferenceField::QuestionVideoLink,
                'type' => AttachmentType::Link,
                'url' => $dto->videoLink,
            ]);

            return $question;
        });

        return (object) $question->load('course', 'attachments');
    }

    public function update(QuestionDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $question = DB::transaction(function () use ($dto, $model) {
            $question = tap($model)->update([
                'category' => $dto->category ? $dto->category : $model->category,
                'question' => $dto->question ? $dto->question : $model->question,
                'option_a' => $dto->optionA ? $dto->optionA : $model->option_a,
                'option_b' => $dto->optionB ? $dto->optionB : $model->option_b,
                'option_c' => $dto->optionC ? $dto->optionC : $model->option_c,
                'option_d' => $dto->optionD ? $dto->optionD : $model->option_d,
                'correct_answer' => $dto->correctAnswer ? $dto->correctAnswer : $model->correct_answer,
                'code_snippets' => $dto->codeSnippets ? $dto->codeSnippets : $model->code_snippets,
                'answer_explanation' => $dto->answerExplanation ? $dto->answerExplanation : $model->answer_explanation,
            ]);

            if ($dto->questionImage)
            {
                $question->attachments()->delete();
                Storage::disk('local')->deleteDirectory('Question/' . $question->id);

                $storedFile = Storage::disk('local')->putFileAs('Question/' . $question->id . '/Images',
                    $dto->questionImage,
                    str()->uuid() . '.' . $dto->questionImage->extension());

                $question->attachment()->create([
                    'reference_field' => AttachmentReferenceField::QuestionImage,
                    'type' => AttachmentType::Image,
                    'url' => basename($storedFile),
                ]);
            }

            $question->attachments()->where('reference_field', AttachmentReferenceField::QuestionVideoLink)->delete();

            $question->attachment()->create([
                'reference_field' => AttachmentReferenceField::QuestionVideoLink,
                'type' => AttachmentType::Link,
                'url' => $dto->videoLink,
            ]);

            return $question;
        });

        return (object) $question->load('course', 'attachments');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $question = DB::transaction(function () use ($id, $model) {
            $model->attachments()->delete();
            Storage::disk('local')->deleteDirectory('Question/' . $model->id);
            return parent::delete($id);
        });

        return (object) $question;
    }

    public function view(int $id): string
    {
        $model = (object) parent::find($id);

        $file = Storage::disk('local')->path('Question/' . $id . '/Images/' . $model->attachment->url);

        if (!file_exists($file))
        {
            throw CustomException::notFound('Image');
        }

        return $file;
    }

    public function download(int $id): string
    {
        $model = (object) parent::find($id);

        $file = Storage::disk('local')->path('Question/' . $id . '/Images/' . $model->attachment->url);

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
            $exists = Storage::disk('local')->exists('Question/' . $model->id);

            if ($exists)
            {
                $model->attachments()->delete();
                Storage::disk('local')->deleteDirectory('Question/' . $model->id);
            }

            $storedFile = Storage::disk('local')->putFileAs('Question/' . $model->id . '/Images',
                $data['image'],
                basename($data['image']));

            array_map('unlink', glob("{$data['finalDir']}/*"));
            rmdir($data['finalDir']);

            $model->attachment()->create([
                'reference_field' => AttachmentReferenceField::QuestionImage,
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
        Storage::disk('local')->deleteDirectory('Question/' . $model->id);
    }
}
