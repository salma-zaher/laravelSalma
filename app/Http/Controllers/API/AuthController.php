<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class AuthController extends Controller
{
    public function register (Request $request){
        try {
            $validateUser= Validator::make($request->all(),
            [  
                'name'=>'required',
                'email'=>'required|string|email|unique:users',
                'password'=>'required|string|min:6',

            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status'=>false,
                    'message'=>'Validation error',
                    'errors'=>$validateUser->errors()
                ],401);
            }


            $user = User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>Hash::make($request->password)
            ]);

            return response()->json([
                'status'=>'success',
                'message'=>'User Successfully Registered',
                'user'=>$user,
                'token'=>$user->createToken("API TOKEN")->plainTextToken
            ],200);

        } catch (\Throwable $th) {
            return response()->json([
                'status'=>false,
                'message'=>$th->getMessage()
            ], 500);
        }
    }




    public function login(Request $request){
        try {
            $validateUser= Validator::make($request->all(),
            [  
                'email'=>'required|email',
                'password'=>'required|string|min:6',

            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status'=>false,
                    'message'=>'Validation error',
                    'errors'=>$validateUser->errors()
                ],401);
            }

            if (!Auth::attempt($request->only(['email','password']))) {
                return response()->json([
                    'status'=>false,
                    'message'=>'Email & Password do not match with our record.'
                ],401);
            }

            $user = User::where('email',$request->email)->first();

            if(Hash::check($request->password,$user->password)){
                $token = $user->createToken("API TOKEN")->plainTextToken;
                $user -> token = $token;
            };

            return response()->json([
                'status'=>'success',
                'message'=>'Logged In Successfully',
                'data'=>$user,
                //'token'=>$user->createToken("API TOKEN")->plainTextToken
            ],200);


        }catch (\Throwable $th) {
            return response()->json([
                'status'=>false,
                'message'=>$th->getMessage()
            ], 500);
        }
    }


    public function getProfile(Request $request){
        try {
            $user_id = $request->user()->id;
            $user = User::find($user_id );
            return response()->json([
                'status'=>'success',
                'message'=>'User Profile',
                'data'=>$user
            ],200);
        }catch (\Throwable $th) {
            return response()->json([
                'status'=>false,
                'message'=>$th->getMessage()
            ], 500);
        }
    }


   /* public function updateProfile(Request $request,$id){
        try {
            $validator = Validator::make($request->all(),[
                'name'=>'required',
                'email'=>'required|string|email|unique:users,id,'.$request->user()->id,
                'password'=>'required|string|min:6',
                'profile_picture'=>'nullable|image',
                'phone_number'=>'nullable|number'

            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'=>false,
                    'message'=>'Validation error',
                    'errors'=>$validator->errors()
            ],401);
            }else{
                $user = User::find($request->user()->id);
                $user->name = $request->name;
                $user->password = $request->password;
                if($request -> profile_picture &&  $request -> profile_picture->isvalid()){
                    $filename = time().'.'. $request -> profile_picture->extenction();
                    $request -> profile_picture->move(public_path('images'),$filename);
                    $path = "public/images/$filename";
                    $user->profile_picture = $path;
                }
                $user->phone_number = $request->phone_number;
                $user->update();
                return response()->json([
                    'status'=>'success',
                    'message'=>'Profile Updated',
                    'user'=>$user,
                ],200);

            }
        }catch (\Throwable $th) {
            return response()->json([
                'status'=>false,
                'message'=>$th->getMessage()
            ], 500);
        }
    } 

    /*public function updateProfile(Request $request,$id){
        $request->validate([
            'profile_picture'=>'image|mimes:jpeg,png,jpg,gif,svg|max"2048'
        ]);
        $user = User::where('id',$id)->first();
        unlink($user->profile_picture);

        $image_name = time().'.'.$request->profile_picture->extenstion();
        $request->profile_picture->move(public_path('users'),$image_name);
        $path ="users/".$image_name; 

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->profile_picture=$path;
        $user->save();
       
    }*/

   /* public function updateProfile(Request $request){
        $user = auth()->user();
        $request->Validate([
            'name'=>'required',
            'email'=>'required|string|email|unique:users',
            'password'=>'required|string|min:6',
            'phone_number'=>'nullable|number'

        ]); 
        $user->updateProfile($request->all());
        return response()->json([
            'status'=>'success',
            'message'=>'Profile Updated',
            
        ],200);
 

    }*/


    public function updateProfile(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            //'password'=>'required|string|min:6',
            'profile_picture'=>'nullable|image|mimes:jpg,bmp,png,svg',
            'phone_number'=>'nullable|min:11',
            'due_date'=>'required|date'
    
        ]); 
        if ($validator->fails()) {
            return response()->json([
                'status'=>false,
                'message'=>'Validation error',
                'errors'=>$validator->errors()
            ],401);
        }
        $user=$request->user();
        if($request->hasFile('profile_picture')){
            if($user->profile_picture){
                $old_path=public_path().'/images/'.$user->profile_picture;
                if(File::exists($old_path)){
                    File::delete($old_path);
                }
            }
            
        $image_name ='profile_picture-' .time().'.'.$request->profile_picture->extension();
        $request->profile_picture->move(public_path('/images/'),$image_name);
        }else{
            $image_name=$user->profile_picture;
        }


        $user->update([
           'name'  => $request->name,
           //'password' => $request->password,
           'profile_picture' => $image_name,
           'phone_number' => $request->phone_number,
           'due_date' => $request->due_date,
        ]);
        return response()->json([
            'status'=>'success',
            'message'=>'Profile Updated',
            'user'=>$user,
        ],200);
    }
    
    public function changePassword(Request $request){
        $validator = Validator::make($request->all(),[
            'old_password'=>'required',
            'password'=>'required|string|min:6',
            'confirm_password'=>'required|same:password'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status'=>false,
                'message'=>'Validation error',
                'errors'=>$validator->errors()
            ],422);
        }
        $user=$request->user();
        if(Hash::check($request->old_password,$user->password)){
            $user ->update([
                'password'=>Hash::make($request->password)
            ]);
            return response()->json([
                'message'=>' Password successfully updated',
            ],200);

        }else{
            return response()->json([
                'message'=>'Old Password does not match',
            ],400);

        }


    }
}
