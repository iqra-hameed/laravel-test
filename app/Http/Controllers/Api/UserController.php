<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    /**
     * Create a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Middleware is applied in routes
    }

    /**
     * Display a listing of users (Admin only)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            // Only admins can view all users
            if (!$user->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied. Admin privileges required.'
                ], 403);
            }

            $perPage = $request->get('per_page', 15);
            $users = User::paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve users',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created user (Admin only)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $currentUser = Auth::user();
            
            // Only admins can create users
            if (!$currentUser->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied. Admin privileges required.'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|string|in:admin,user',
                'phone' => 'nullable|string|max:20',
                'bio' => 'nullable|string|max:1000',
                'avatar' => 'nullable|url',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $userData = $request->only(['name', 'email', 'phone', 'bio', 'avatar', 'role']);
            $userData['password'] = Hash::make($request->password);

            $user = User::create($userData);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified user
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $currentUser = Auth::user();
            $targetUser = User::findOrFail($id);
            
            // Users can only view their own profile, admins can view any
            if (!$currentUser->isAdmin() && $currentUser->id != $targetUser->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. You can only view your own profile.'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => $targetUser
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified user
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $currentUser = Auth::user();
            $targetUser = User::findOrFail($id);
            
            // Users can only update their own profile, admins can update any
            if (!$currentUser->isAdmin() && $currentUser->id != $targetUser->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. You can only update your own profile.'
                ], 403);
            }

            $rules = [
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $targetUser->id,
                'phone' => 'nullable|string|max:20',
                'bio' => 'nullable|string|max:1000',
                'avatar' => 'nullable|url',
                'password' => 'sometimes|required|string|min:8|confirmed',
            ];
            
            // Only admins can change roles
            if ($currentUser->isAdmin()) {
                $rules['role'] = 'sometimes|required|string|in:admin,user';
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = $request->only(['name', 'email', 'phone', 'bio', 'avatar']);
            
            if ($request->has('password')) {
                $updateData['password'] = Hash::make($request->password);
            }
            
            if ($currentUser->isAdmin() && $request->has('role')) {
                $updateData['role'] = $request->role;
            }

            $targetUser->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $targetUser->fresh()
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified user (Admin only)
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $currentUser = Auth::user();
            
            // Only admins can delete users
            if (!$currentUser->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied. Admin privileges required.'
                ], 403);
            }

            $targetUser = User::findOrFail($id);
            
            // Prevent admin from deleting themselves
            if ($currentUser->id == $targetUser->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot delete your own account'
                ], 400);
            }

            $targetUser->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current user's profile
     *
     * @return JsonResponse
     */
    public function profile(): JsonResponse
    {
        try {
            $user = Auth::user();
            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update current user's profile
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateProfile(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
                'phone' => 'nullable|string|max:20',
                'bio' => 'nullable|string|max:1000',
                'avatar' => 'nullable|url',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = $request->only(['name', 'email', 'phone', 'bio', 'avatar']);
            $user->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => $user->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'password' => 'required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();

            // Verify current password
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect'
                ], 400);
            }

            // Update password
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update password: ' . $e->getMessage()
            ], 500);
        }
    }
}
