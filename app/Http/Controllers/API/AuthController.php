<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Auth;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(),
            [
                'name'      => 'required|string|max:255',
                'email'     => 'required|string|email|max:255|unique:users',
                'password'  => 'required|string|min:8'
            ]);

            if($validator->fails())
            {
                return response()->json($validator->errors());       
            }

            $user = User::create(
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'fcm_token' => $request->token,
                    'password' => Hash::make($request->password)
                ]
            );

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()
                ->json(
                    [
                        'message' => 'Selamat ' . $user->name . ' berhasil register',
                        'data' => $user,
                        'access_token' => $token, 
                        'token_type' => 'Bearer', 
                    ],
                200);

        }

        catch (\Exception $error)
        {
            return response()
            ->json(
                [
                    'message'   => 'Err',
                    'errors'    => $error->getMessage(),
                ],
            500);
        }   
    }

    public function login(Request $request)
    {
        try 
        {
            if (!Auth::attempt($request->only('email', 'password')))
            {
                return response()
                    ->json(
                        [
                            'message' => 'Unauthorized'
                        ], 
                    401);
            }

            auth()->user()->tokens()->delete();

            $user = User::where('email', $request['email'])->firstOrFail();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()
                ->json(
                    [
                        'message'       => 'Hi '.$user->name.', welcome to home',
                        'data'          => $user,
                        'access_token'  => $token,
                        'token_type'    => 'Bearer', 
                    ],
                200);
        }

        catch (\Exception $error)
        {
            return response()
            ->json(
                [
                    'message'   => 'Err',
                    'errors'    => $error->getMessage(),
                ],
            500);
        }   
    }

    // Forgot Password
    public function submitForgotPasswordForm(Request $request)
    {
        $input = $request->only('email');

        $validator = Validator::make($input, [
            'email' => 'required|email|exists:users',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $emailsent =  Password::sendResetLink($input);

        if ($emailsent == Password::RESET_LINK_SENT) {
            $message = "Mail send successfully";
        } else {
            $message = "Email could not be sent to this email address";
        }

        return response()
            ->json(
                [
                    'message' => $message,
                ],
                200
            );
    }

    //Reset Password
    public function submitResetPasswordForm(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        

        return response()
            ->json(
                [
                    'message' => 'Password berhasil di ubah',
                ],
                200
            );
    }


    // method for user logout and delete token
    public function logout()
    {
        
        auth()->user()->tokens()->delete();

        return [
            'message' => Auth::user()->name . ' successfully logged out and the token was successfully deleted'
        ];
    }
}
