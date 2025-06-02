<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    function __invoke()
    {
        return view('profile');
    }

    public function changePassword(Request $request)
    {
        
        $validated = $request->validate([
            'old_password' => ['required','min:8'],
            'new_password' => ['required', 'min:8', 'confirmed']
        ]);

        if(!Hash::check($validated['old_password'], auth()->user()->password)) {
            return $this->error("Provided old password does not match!", 404);
        }

        $user = auth()->user();
        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return $this->success([], "Your password changed!");
    }

    function updateProfilePic(Request $request)
    {
        if($request->hasFile('file')) {
            $user = auth()->user();
            if(!is_null($user->image)) {
                $path = public_path('/storage/'). $user->image;
                if(file_exists($path)){
                    unlink($path);
                }
            }
            $profilePath = 'uploads/' . $request['file']->getClientOriginalName();
            Storage::disk('public')->put($profilePath, file_get_contents($request['file']));
            $user->update(['image' => $profilePath]);
            return $this->success([],"Profile updated successfully");
        }
    }
}
