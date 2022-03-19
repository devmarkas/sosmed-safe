<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    public function show($id)
    {
        try
        {
            $user = User::find($id);
            return response()
            ->json(
                [
                    'message'   => 'Data '.$user->name.', Berhasil Diambil',
                    'data'      => $user,
                ],
            200);

        } 
        catch (\Exception $error)
        {
            return response()->json(
                [
                    'message'   => 'Err',
                    'errors'    => $error->getMessage(),
                ],
            500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = $request->validate(
            [
                'name'              => 'string|max:255',
                'photo_profile'     => 'image|file|max:1024|mimes:png,jpg,jpeg',
            ]
        );

        try
        {
            $user = User::find($id);
            if($request->file('photo_profile'))
            {
                $path = $request->file('photo_profile')->store('profile-picture');
                $validator['photo_profile'] = $path;

                if(File::exists(public_path($user->photo_profile)))
                {
                    File::delete($user->photo_profile);
                }
            }

            $user->update($validator);

            return response()->json([
                'success' => true,
                'message' => 'Data '.$user->name.', Berhasil Diupdate',
                'data' => $user,
            ], 200);
        } 
        catch (\Exception $error)
        {
            return response()->json(
                [
                    'message' => 'Err',
                    'errors' => $error->getMessage(),
                ], 
            500);
        }

    }
}
