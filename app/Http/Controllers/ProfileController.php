<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $user = $request->user()->load('media');
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'user' => $user,
        ]);
    }

public function update(ProfileUpdateRequest $request): RedirectResponse
{
    $user = $request->user();
    $user->fill($request->validated());

    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    try {
        if ($request->hasFile('avatar')) {
            if ($request->file('avatar')->isValid()) {
                \Log::info('Avatar file is valid');
                
                // Проверка прав доступа
                $uploadPath = storage_path('app/public/avatars');
                if (!is_writable($uploadPath)) {
                    \Log::error('Upload directory is not writable: ' . $uploadPath);
                    throw new \Exception('Upload directory is not writable');
                }

                // Проверка типа файла
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($request->file('avatar')->getMimeType(), $allowedTypes)) {
                    \Log::error('Invalid file type: ' . $request->file('avatar')->getMimeType());
                    throw new \Exception('Invalid file type');
                }

                // Проверка размера файла
                $maxSize = 5 * 1024 * 1024; // 5 MB
                if ($request->file('avatar')->getSize() > $maxSize) {
                    \Log::error('File size exceeds limit: ' . $request->file('avatar')->getSize());
                    throw new \Exception('File size exceeds limit');
                }

                $user->clearMediaCollection('avatar');
                $media = $user->addMediaFromRequest('avatar')
                     ->usingName($user->name . '_avatar')
                     ->usingFileName(time() . '_' . $request->file('avatar')->getClientOriginalName())
                     ->toMediaCollection('avatar');
                
                \Log::info('Media added successfully: ' . $media->id);
            } else {
                \Log::error('Avatar file is not valid');
                throw new \Exception('Avatar file is not valid');
            }
        }

        $user->save();
        $user->load('media'); // Добавлено здесь
        \Log::info('User profile updated successfully: ' . $user->id);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    } catch (\Exception $e) {
        \Log::error('Error updating profile: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        return Redirect::route('profile.edit')->withErrors(['avatar' => 'Failed to update profile: ' . $e->getMessage()]);
    }
}

    
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
