<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profile;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\Console\Helper\TreeNode;

class ProfileController extends Controller
{
    public function store(StoreProfileRequest $request)
    {
        try {
            if (Auth::user()->profile) {
                return response()->json([
                    'message' => 'You already have a profile'
                ], 422);
            }
            $validate = $request->validated();
            $validate['user_id'] = Auth::user()->id;
            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('images', 'public');
                $validate['avatar'] = $path;
            }
            $profile = Profile::create($validate);


            return response()->json([
                'profile' => $profile,
            ], 201);
        } catch (Exception $e) {
            return response()->json(
                ['message' => 'Failed to create profile'],
                500

            );
        }
    }

    public function update(UpdateProfileRequest $request)
    {
        $profile = Auth::user()->profile;

        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        $data = $request->validated();

        if ($request->hasFile('avatar')) {

            if ($profile->avatar) {
                Storage::disk('public')->delete($profile->avatar);
            }

            $path = $request->file('avatar')->store('images', 'public');
            $data['avatar'] = $path;
        }

        $profile->update($data);

        return response()->json([
            'message' => 'Profile updated successfully',
            'profile' => $profile->fresh()
        ], 200);
    }

    public function show()
    {
        try {
            $profile = Auth::user()->profile;
            return response()->json(
                [
                    'profile' => $profile
                ],
                200
            );
        } catch (Exception $e) {
            return response()->json(
                ['message' => 'profile not found'],
                404
            );
        }
    }
}
