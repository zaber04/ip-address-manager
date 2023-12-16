<?php

declare(strict_types=1);

namespace IpHandler\Http\Controllers;

use IpHandler\Models\AuditTrail;
use IpHandler\Traits\PaginationTrait;
use Gateway\Traits\ApiResponseTrait;
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
    use ApiResponseTrait;
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
        // @TODO: Show based on user session id
        try {
            // Valid request parameters?
            $this->validatePagination($request);

            // Default pagination values
            $pagination = $this->getPaginationParams($request);

            // Fetch with latest entry first and sort (stable api if sorted by created_at)
            $auditTrails = AuditTrail::orderBy($pagination['sort_field'], $pagination['sort_order'])->paginate($pagination['per_page'], ['*'], 'page', $pagination['page']);

            return $this->jsonResponseWith(['data' => $auditTrails], JsonResponse::HTTP_OK);
        } catch (ValidationException | ModelNotFoundException | QueryException $e) {
            $errorInfo = ['url' => $request->path(), 'function' => 'AuditTrailController@index'];
            return $this->handleException($request, $e, $errorInfo);
        } catch (\Exception $e) {
            $errorInfo = ['url' => $request->path(), 'function' => 'AuditTrailController@index'];
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
    public function showByUserId(Request $request, string  $id): JsonResponse
    {
        try {
            $validator = Validator::make(['user_id' => $id], ['user_id' => 'required|exists:users,id']);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // Valid request parameters?
            $this->validatePagination($request);

            // Default pagination values
            $pagination = $this->getPaginationParams($request);

            // $id referes to 'user_id' field of the table
            try {
                $auditTrails = AuditTrail::with('user')
                    ->where('user_id', $id)
                    ->where('session_id', function ($query) use ($id) {
                        $query->select('session_id')
                            ->from('audit_trails')
                            ->where('user_id', $id)
                            ->orderBy('created_at', 'desc')
                            ->limit(1);
                    })
                    ->orderBy($pagination['sort_field'], $pagination['sort_order'])
                    ->paginate($pagination['per_page'], ['*'], 'page', $pagination['page']);

                // audit trails --> empty is ok

                return $this->jsonResponseWith(['data' => $auditTrails], JsonResponse::HTTP_OK);

            } catch (\Exception $e) {
                // Rethrow the exception
                throw new QueryException($e->getMessage(), $e->getPrevious(), $e->getCode(), $e);
            }
        } catch (ValidationException | ModelNotFoundException | QueryException $e) {
            $errorInfo = ['url' => $request->path(), 'function' => 'AuditTrailController@show'];
            return $this->handleException($request, $e, $errorInfo);
        } catch (\Exception $e) {
            $errorInfo = ['url' => $request->path(), 'function' => 'AuditTrailController@show'];
            return $this->handleException($request, $e, $errorInfo, JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified user's last session's Audit Trail.
     *
     * @param  Request $request
     * @param  string  $id
     * @return JsonResponse
     */
    public function showByAuditId(Request $request, string  $id): JsonResponse
    {
        try {
            $validator = Validator::make(['id' => $id], ['id' => 'required|uuid']);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $auditTrail = AuditTrail::findOrFail($id);

            return $this->jsonResponseWith(['data' => ['data' => [$auditTrail]]], JsonResponse::HTTP_OK);
        } catch (ValidationException | ModelNotFoundException | QueryException $e) {
            $errorInfo = ['url' => $request->path(), 'function' => 'AuditTrailController@show'];
            return $this->handleException($request, $e, $errorInfo);
        } catch (\Exception $e) {
            $errorInfo = ['url' => $request->path(), 'function' => 'AuditTrailController@show'];
            return $this->handleException($request, $e, $errorInfo, JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}
