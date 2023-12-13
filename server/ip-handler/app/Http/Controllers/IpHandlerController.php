<?php

declare(strict_types=1);

namespace IpHandler\Http\Controllers;

use Gateway\Traits\ApiResponse;
use Gateway\Traits\LoggingTrait;
use Gateway\Traits\ExceptionHandlerTrait;
use IpHandler\Models\IpAddress;
use IpHandler\Traits\PaginationTrait;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\Controller as BaseController;

class IpHandlerController extends BaseController
{
    use ApiResponse;
    use LoggingTrait;
    use ExceptionHandlerTrait;
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
            $this->validate($request, [
                'ip' => 'required|ip',
                'label' => 'required|string',
            ]);

            $ipAddress = IpAddress::create([
                'ip'    => $request->input('ip'),
                'label' => $request->input('label'),
            ]);

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
}
