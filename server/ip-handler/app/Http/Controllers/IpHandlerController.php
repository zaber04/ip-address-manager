<?php

declare(strict_types=1);

namespace IpHandler\Http\Controllers;

use Gateway\Enums\ActionEnum;
use Gateway\Traits\ApiResponseTrait;
use Gateway\Traits\LoggingTrait;
use Gateway\Traits\ExceptionHandlerTrait;
use IpHandler\Models\IpAddress;
use IpHandler\Traits\AuditTrailTrait;
use IpHandler\Traits\PaginationTrait;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\Controller as BaseController;

class IpHandlerController extends BaseController
{
    use ApiResponseTrait;
    use ExceptionHandlerTrait;
    use LoggingTrait;
    use AuditTrailTrait;
    use PaginationTrait;

    /**
     * Display a paginated listing of IP addresses.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // Validate pagination parameters
            $this->validatePagination($request);

            // default pagination values
            $pagination = $this->getPaginationParams($request);

            // Fetch with latest entry first and sort (stable api if sorted by created_at)
            $ipAddresses = IpAddress::orderBy($pagination['sort_field'], $pagination['sort_order'])
                ->paginate($pagination['per_page'], ['*'], 'page', $pagination['page']);

            // Log the request and response
            // $this->logRequestAndResponse(['function' => 'IpHandlerController@index','url' => $request->path(), 'query' => $request->query()], $ipAddresses);

            return $this->jsonResponseWith(['ip_addresses' => $ipAddresses], JsonResponse::HTTP_OK);
        } catch (ValidationException | ModelNotFoundException | QueryException $e) {
            $errorInfo = ['url' => $request->path(), 'function' => 'IpHandlerController@index'];
            return $this->handleException($request, $e, $errorInfo);
        } catch (\Exception $e) {
            $errorInfo = ['url' => $request->path(), 'function' => 'IpHandlerController@index'];
            return $this->handleException($request, $e, $errorInfo, JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Store a newly created IP address in the database.
     *
     * @param  Request  $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $this->validate($request, IpAddress::$rules);

            // Start a database transaction
            DB::beginTransaction();

            try {
                // store ip
                $ipAddress = IpAddress::createWithAttributes(
                    $request->input('ip'),
                    $request->input('label')
                );

                // store audit log --> this can throw error and retrun error response
                $this->storeAuditEvent($request, $ipAddress->id, "", ActionEnum::INSERT);

                // Commit the transaction
                DB::commit();
            } catch (\Exception $e) {
                // Rollback the transaction
                DB::rollBack();

                // Re-throw the exception to let it propagate
                throw $e;
            }

            return $this->jsonResponseWith(['ip_address' => $ipAddress], JsonResponse::HTTP_CREATED);
        } catch (ValidationException | ModelNotFoundException | QueryException $e) {
            // Rollback the changes
            DB::rollBack();

            $errorInfo = ['url' => $request->path(), 'function' => 'IpHandlerController@store'];
            return $this->handleException($request, $e, $errorInfo);
        } catch (\Exception $e) {
            // Rollback the changes
            DB::rollBack();

            $errorInfo = ['url' => $request->path(), 'function' => 'IpHandlerController@store'];
            return $this->handleException($request, $e, $errorInfo, JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified IP address.
     *
     * @param  Request $request
     * @param  string  $id
     * @return JsonResponse
     */
    public function show(Request $request, string  $id): JsonResponse
    {
        try {
            $validator = Validator::make(['id' => $id], ['id' => 'required|uuid']);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $ipAddress = IpAddress::findOrFail($id);

            return $this->jsonResponseWith(['ip_address' => $ipAddress], JsonResponse::HTTP_OK);
        } catch (ValidationException | ModelNotFoundException | QueryException $e) {
            $errorInfo = ['function' => 'IpHandlerController@show'];
            return $this->handleException($request, $e, $errorInfo);
        } catch (\Exception $e) {
            $errorInfo = ['function' => 'IpHandlerController@show'];
            return $this->handleException($request, $e, $errorInfo, JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified IP address in the database.
     *
     * @param  Request  $request
     * @param  string  $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            // Validate the ID parameter
            $this->validateId($id);

            // Validate the request data
            // IpAddress::validate(['label' => $request->input('label')]);
            $this->validate($request, [
                'label' => IpAddress::$rules['label']
            ]);

            // Find the IpAddress by ID or throw a ModelNotFoundException
            $ipAddress = IpAddress::findOrFail($id);

            // Start a database transaction
            DB::beginTransaction();

            try {
                // Update the IpAddress with the new label
                $previousValue = $ipAddress->label;
                $ipAddress->update(['label' => $request->input('label')]);

                // store audit log --> this can throw error and retrun error response
                $this->storeAuditEvent($request, $id, $previousValue);

                // Commit the transaction
                DB::commit();
            } catch (\Exception $e) {
                // Rollback the transaction
                DB::rollBack();

                // Re-throw the exception to let it propagate
                throw $e;
            }

            return $this->jsonResponseWith(['ip_address' => $ipAddress], JsonResponse::HTTP_OK);
        } catch (ValidationException | ModelNotFoundException | QueryException $e) {
            $errorInfo = ['function' => 'IpHandlerController@update'];
            return $this->handleException($request, $e, $errorInfo);
        } catch (\Exception $e) {
            $errorInfo = ['function' => 'IpHandlerController@update'];
            return $this->handleException($request, $e, $errorInfo, JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Validate the uuid of an id before update
     */
    private function validateId(string $id): void
    {
        // Validate the ID parameter using the Validator
        $validator = Validator::make(['id' => $id], ['id' => 'required|uuid']);

        // Throw a ValidationException if validation fails
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
