<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationStoreRequest;
use App\Http\Resources\LocationResource;
use App\Services\LocationForecastService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * UserController constructor.
     *
     * Initializes the UserController class.
     */
    public function __construct(
        private readonly UserService $userService,
        private readonly LocationForecastService $locationForecastService
    ) {}

    /**
     * Retrieve the locations associated with the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming HTTP request.
     */
    public function getUserLocations(Request $request): mixed
    {
        try {
            $userLocations = $this->userService->getUserLocations($request->user());

            return LocationResource::collection($userLocations);
        } catch (\Exception $e) {
            \Log::error('Error fetching user locations: '.$e->getMessage());

            return response()->json([
                'message' => 'Error fetching user locations',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(LocationStoreRequest $request): ?JsonResponse
    {
        try {
            $this->locationForecastService->store($request);

            return response()->json([
                'message' => 'Location saved successfully!',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error('Error saving user locations: '.$e->getMessage());

            return response()->json([
                'message' => 'Error saving user location',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a weather location.
     */
    public function destroy(Request $request, string $locationId, string $date): ?JsonResponse
    {
        try {
            $user = $request->user();
            $this->locationForecastService->deleteLocation($locationId, $date, $user);

            return response()->json([
                'message' => 'Location deleted successfully!',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error('Error fetching forecast: '.$e->getMessage());

            return response()->json([
                'message' => 'Error deleting user location',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
