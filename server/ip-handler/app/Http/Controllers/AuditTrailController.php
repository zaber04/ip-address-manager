<?php

declare(strict_types=1);

namespace IpHandler\Http\Controllers;

use IpHandler\Models\AuditTrail;
use IpHandler\Traits\PaginationTrait;
use Gateway\Traits\ApiResponse;
use Gateway\Traits\LoggingTrait;
use Gateway\Traits\ExceptionHandlerTrait;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\Controller as BaseController;

class AuditTrailController extends BaseController
{
    use ApiResponse;
    use LoggingTrait;
    use ExceptionHandlerTrait;
    use PaginationTrait;

    /**
     * Display a paginated listing of audit trails.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // Valid request parameters?
            $this->validatePagination($request);

            // Default pagination values
            $pagination = $this->getPaginationParams($request);

            /**
             * Fetch with latest entry first and sort (stable api if sorted by created_at)
             */
            $auditTrails = AuditTrail::orderBy($pagination['sort_field'], $pagination['sort_order'])
                ->paginate($pagination['per_page'], ['*'], 'page', $pagination['page']);

            // Log the request and response
            // $this->logRequestAndResponse(['function' => 'AuditTrailController@index','url' => $request->path(), 'query' => $request->query()], $auditTrails);

            return $this->jsonResponseWith(['audit_trails' => $auditTrails], JsonResponse::HTTP_OK);
        } catch (ValidationException | ModelNotFoundException | QueryException $e) {
            $errorInfo = ['url' => $request->path(), 'function' => 'AuditTrailController@index'];
            return $this->handleException($request, $e, $errorInfo);
        } catch (\Exception $e) {
            $errorInfo = ['url' => $request->path(), 'function' => 'AuditTrailController@index'];
            return $this->handleException($request, $e, $errorInfo, JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Store a newly created audit trail in the database.
     * This should be instantiated when an user logs in
     *
     * @param  Request  $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Start an audit trail when a user logs in

            return $this->jsonResponseWith(['audit_trail' => []], JsonResponse::HTTP_CREATED);
        } catch (ValidationException | ModelNotFoundException | QueryException $e) {
            $errorInfo = ['url' => $request->path(), 'function' => 'AuditTrailController@store'];
            return $this->handleException($request, $e, $errorInfo);
        } catch (\Exception $e) {
            $errorInfo = ['url' => $request->path(), 'function' => 'AuditTrailController@store'];
            return $this->handleException($request, $e, $errorInfo, JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified Audit Trail.
     *
     * @param  Request $request
     * @param  string  $id
     * @return JsonResponse
     */
    public function show(Request $request, string  $id): JsonResponse
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|uuid',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $auditTrail = AuditTrail::findOrFail($id);

            return $this->jsonResponseWith(['audit_trail' => $auditTrail], JsonResponse::HTTP_OK);
        } catch (ValidationException | ModelNotFoundException | QueryException $e) {
            $errorInfo = ['url' => $request->path(), 'function' => 'AuditTrailController@show'];
            return $this->handleException($request, $e, $errorInfo);
        } catch (\Exception $e) {
            $errorInfo = ['url' => $request->path(), 'function' => 'AuditTrailController@show'];
            return $this->handleException($request, $e, $errorInfo, JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}
