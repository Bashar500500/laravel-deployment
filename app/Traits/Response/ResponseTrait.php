<?php

namespace App\Traits\Response;
use Illuminate\Http\JsonResponse;
use App\Enums\Trait\ModelName;
use App\Enums\Trait\FunctionName;
use App\Enums\Trait\ResponseStatus;
use App\Exceptions\InternalException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
// use Illuminate\Contracts\Routing\ResponseFactory;

trait ResponseTrait
{
    public object $data;
    public ModelName $modelName;
    public FunctionName $functionName;
    public InternalException $internalException;
    public string $file;
    public string $zip;

    public function setData(object $data)
    {
        $this->data = $data;
        return $this;
    }

    public function setFunctionName(FunctionName $functionName)
    {
        $this->functionName = $functionName;
        return $this;
    }

    public function setModelName(ModelName $modelName)
    {
        $this->modelName = $modelName;
        return $this;
    }

    public function setInternalException(InternalException $internalException)
    {
        $this->internalException = $internalException;
        return $this;
    }

    public function setFile(string $file)
    {
        $this->file = $file;
        return $this;
    }

    public function setZip(string $zip)
    {
        $this->zip = $zip;
        return $this;
    }

    public function successResponse(): JsonResponse
    {
        return response()->json([
            'status' => ResponseStatus::Success->getMessage(),
            'message' => $this->modelName->getMessage() . $this->functionName->getMessage(),
            'data' => $this->data,
            ], 200);
    }

    public function errorResponse(): JsonResponse
    {
        $code = $this->internalException->getInternalCode();

        return response()->json([
            'status' => ResponseStatus::Error->getMessage(),
            'code' => $code->value,
            'message' => $this->internalException->getMessage(),
            'description' => $this->internalException->getDescription(),
            'link' => $code->getLink(),
        ], $this->internalException->getCode());
    }

    public function viewFileResponse(): BinaryFileResponse
    {
        return response()->file($this->file, [
            'X-Sendfile' => $this->file,
            'Content-Type' => mime_content_type($this->file),
        ])->deleteFileAfterSend(true);
    }

    public function downloadFileResponse(): BinaryFileResponse
    {
        return response()->file($this->file, [
            'X-Sendfile' => $this->file,
            'Content-Type' => mime_content_type($this->file),
            'Content-Disposition' => 'attachment; filename="' . basename($this->file) . '"',
        ])->deleteFileAfterSend(true);
    }

    public function downloadZipResponse(): BinaryFileResponse
    {
        return response()->file($this->zip, [
            'X-Sendfile' => $this->zip,
            'Content-Type' => mime_content_type($this->zip),
            'Content-Disposition' => 'attachment; filename="' . basename($this->zip) . '"',
        ])->deleteFileAfterSend(true);
    }

    // public function viewFileResponse(): BinaryFileResponse
    // {
    //     return response()->file($this->file, [
    //         'X-Accel-Redirect' => "/protected/{$this->file}",
    //         'Content-Type' => mime_content_type($this->file),
    //     ]);
    // }

    // public function downloadFileResponse(): ResponseFactory
    // {
    //     return response(
    //         headers: [
    //             'X-Accel-Redirect' => "/protected/{$this->file}",
    //             'Content-Type' => mime_content_type($this->file),
    //             'Content-Disposition' => 'attachment; filename="' . basename($this->file) . '"',
    //         ],
    //         status: 200,
    //     );
    // }

    // public function downloadZipResponse(): ResponseFactory
    // {
    //     return response(
    //         headers: [
    //             'X-Accel-Redirect' => "/protected/{$this->file}",
    //             'Content-Type' => mime_content_type($this->file),
    //             'Content-Disposition' => 'attachment; filename="' . basename($this->zip) . '"',
    //         ],
    //         status: 200,
    //     );
    // }
}
