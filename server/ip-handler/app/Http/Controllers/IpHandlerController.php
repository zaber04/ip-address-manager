<?php

declare(strict_types=1);

namespace IpHandler\Http\Controllers;

use IpHandler\Models\IpAddress;
use Gateway\Traits\ApiResponse;
use Gateway\Traits\LoggingTrait;
use Gateway\Traits\ExceptionHandlerTrait;


use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;


class IpHandlerController extends BaseController
{
    use ApiResponse;
    use LoggingTrait;
    use ExceptionHandlerTrait;

    /**
     * Display a paginated listing of IP addresses.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // Valid request parameters?
            $this->validate($request, [
                'page'       => 'integer|min:1',
                'per_page'   => 'integer|min:1|max:100',
                'sort_field' => Rule::in(['created_at', 'ip', 'id']), // valid sort fields
                'sort_order' => Rule::in(['asc', 'desc'])
            ]);

            // Default pagination values
            $pagination = $this->getPaginationParams($request);

            // Fetch with latest first and sort (stable api if sorted by created_at)
            $ipAddresses = IpAddress::orderBy($pagination['sort_field'], $pagination['sort_order'])
                ->paginate($pagination['per_page'], ['*'], 'page', $pagination['page']);

            // Log the request and response
            // $this->logRequestAndResponse(['function' => 'IpHandlerController@index','url' => $request->path(), 'query' => $request->query()], $ipAddresses);

            return $this->jsonResponseWith(['ip_addresses' => $ipAddresses], JsonResponse::HTTP_OK);
        } catch (ValidationException | ModelNotFoundException | QueryException $e) {
            $errorInfo = ['url' => $request->path(), 'function' => 'IpHandlerController@index'];
            return $this->handleException($request, $e, $errorInfo);
        } catch (\Exception $e) {
            $errorInfo = ['url' => $request->path(), 'function' => 'index'];
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
            $this->validate($request, [
                'ip' => 'required|ip',
                'label' => 'required|string',
            ]);

            $ipAddress = IpAddress::create([
                'ip'    => $request->input('ip'),
                'label' => $request->input('label'),
            ]);

            // $this->logRequestAndResponse(['function' => 'IpHandlerController@store','url' => $request->path(), 'query' => $request->query()], $ipAddresses);

            return $this->jsonResponseWith(['ip_address' => $ipAddress], JsonResponse::HTTP_CREATED);
        } catch (ValidationException | ModelNotFoundException | QueryException $e) {
            $errorInfo = ['url' => $request->path(), 'function' => 'IpHandlerController@store'];
            return $this->handleException($request, $e, $errorInfo);
        } catch (\Exception $e) {
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
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|uuid',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $ipAddress = IpAddress::findOrFail($id);

            // $this->logRequestAndResponse(['function' => 'IpHandlerController@store','url' => $request->path(), 'query' => $request->query()], $ipAddresses);

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
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|uuid',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $this->validate($request, [
                'label' => 'required|string|max:255',
            ]);

            $ipAddress = IpAddress::findOrFail($id);
            $ipAddress->update([
                'label' => $request->input('label'),
            ]);

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
     * Archive the specified IP address from the database.
     *
     * @param  string  $id
     * @return JsonResponse
     */
    public function archive(string $id): JsonResponse
    {
        return $this->jsonResponseWith(['message' => 'Archive is not supported'], JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Get pagination parameters.
     *
     * @param Request $request
     * @return array
     */
    private function getPaginationParams(Request $request): array
    {
        // we can use .env for these default values
        return [
            'page'       => $request->input('page', 1),
            'per_page'   => $request->input('per_page', 20),
            'sort_field' => $request->input('sort_field', 'created_at'),
            'sort_order' => $request->input('sort_order', 'desc'),
        ];
    }
}
