<?php

declare(strict_types=1);

namespace Gateway\Traits;

use Gateway\Models\ErrorLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

trait LoggingTrait
{
    /**
     * Validate and format the error data array based on the ErrorLog model.
     *
     * @param  array  $errorData  Should follow ErrorLog model
     * @return array
     */
    private function validateAndFormatErrorData(array $errorData): array
    {
        $fillableAttributes = (new ErrorLog())->getFillable();

        // all required keys present?
        $requiredKeys = array_diff($fillableAttributes, array_keys($errorData));
        foreach ($requiredKeys as $key) {
            $errorData[$key] = null;
        }

        return $errorData;
    }

    /**
     * Log and respond with an error JSON response.
     *
     * @param  array  $errorData
     * @return JsonResponse
     */
    public function logAndErrorResponse(array $errorData): JsonResponse
    {
        try {
            // Validate and format the error data
            $validatedErrorData = $this->validateAndFormatErrorData($errorData);

            Log::error('Error occurred: ' . json_encode($validatedErrorData));

            // Save error in the database
            $this->saveErrorToDatabase($validatedErrorData);
        } catch (\Exception $e) {
            $this->logSecondaryError('Secondary error occurred: ' . $e->getMessage());

            return response()->json(['error' => 'Internal Server Error'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Save error data to the database.
     *
     * @param  array  $errorData
     * @return void
     */
    private function saveErrorToDatabase(array $errorData): void
    {
        try {
            $errorLog = new ErrorLog($errorData);
            $errorLog->save();
        } catch (\Exception $e) {
            Log::error('Error saving to database: ' . $e->getMessage());
        }
    }
}



