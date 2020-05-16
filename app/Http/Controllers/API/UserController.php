<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class   UserController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('q')) {
              $users = User::where(function($query) use ($request){
                    $query->where('name','LIKE','%'.$request->q.'%')->orWhere('email','LIKE','%'.$request->q.'%');
              })->paginate(5);
        }else{
            $users = User::latest()->paginate(5);
        }
        return $users;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|max:191',
            'bio'      => 'nullable',
            'type'     => 'required'
        ]);
        $data['password'] = bcrypt($request->password);

        $user = User::create($data);
        return $user;
    }


    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $user = auth('api')->user();
        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|string|email|max:100|unique:users,email,'.$id,
            'bio'      => 'nullable',
            'type'     => 'required'
        ]);

        if ($request->has('password') && $request->photo !== null) {
            $data['password'] = bcrypt($request->password);
        }

        if( $request->has('photo') && $request->photo !== null) {
            @unlink(public_path('/uploads/profile/').$user->photo);
            $photo = $request->photo;
            $photoInfo = explode(";base64,", $photo);
            $imgExt = str_replace('data:image/', '', $photoInfo[0]);
            $photo = str_replace(' ', '+', $photoInfo[1]);
            $photoName = "profile-".time().".".$imgExt;
            \File::put(public_path(). '/uploads/profile/' . $photoName, base64_decode($photo));
            $data['photo'] = $photoName;
        }

        $user->update($data);

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');
        $user = User::findOrFail($id);
        $user->delete();
        return ['message' => 'User Deleted'];
    }
}
