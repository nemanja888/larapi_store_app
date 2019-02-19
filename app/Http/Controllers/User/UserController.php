<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Mail\UserCreated;
use App\User;
use Illuminate\Support\Facades\Mail;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return $this->showAll($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationCode();
        $data['admin'] = User::REGULAR_USER;

        $user = User::create($data);

        return $this->showOne($user, 201);
    }


    /**
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return $this->showOne($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email') && $request->email != $user->email) {
            $user->verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        if ($request->has('admin')) {
            if (!$user->isVerified()) {
                return $this->errorResponse('Only verified users can modify the admin field', 409);
            }

            $user->admin = $request->admin;
        }

        if (!$user->isDirty()) {
            return $this->errorResponse('You need to specify a different value to update', 409);
        }

        $user->save();

        return $this->showOne($user);
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['data' => $user], 200);
    }

    public function verify($token)
    {
        $user = User::where('verification_token', $token)->firstOrFail();
        $user->verified = User::VERIFIED_USER;
        $user->verification_token = null;
        $user->save();

        return $this->showMessage('The account has been verified successfully');
    }

    public function resend(User $user)
    {
        if ($user->isVerified()) {
            return $this->errorResponse('This user is already verified', 409);
        }
        retry(5, function() use ($user) {
            Mail::to($user)->send(new UserCreated($user));
        }, 100);

        return $this->showMessage('The verification email has been resend');
    }
}
