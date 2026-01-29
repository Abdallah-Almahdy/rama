<?php
/* app\Http\Controllers\Api\AuthController.php */

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\customer_info;
use Illuminate\Validation\Rules;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Auth\Events\Registered;
use App\Http\Resources\userDataResource;


class AuthController extends Controller
{
    //
   public function register(Request $request): JsonResponse
    {

      $request->validate([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'string', 'max:255', 'unique:' . User::class],
    'password' => ['required', 'confirmed', Rules\Password::defaults()],
   
    'addressFloorNumber' => ['nullable', 'string'],
    'addressBuildingNumber' => ['nullable', 'string'],
    'addressStreet' => ['nullable', 'string'],
    'addressCity' => ['nullable', 'string'],
    'addressCountry' => ['nullable', 'string'],
    'disSign' => ['nullable', 'string'],
    'phonenum' => ['nullable', 'string'],
    'addressApartmentNumber' => ['nullable', 'string'],
]);

        $already_eaused_email = User::where('email', $request->email)->first();
        if ($already_eaused_email) {
            return response()->json(
                [
                    "user" => Null,
                    "message" => "phone-already-in-use",
                    "stus" => "failed",
                ],
                200
            );
        }




        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);



        



        $user = customer_info::create([
            'user_id' => $user['id'],
            'firstName' => $request->name,
            'lastName' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'birthDate' => $request->birthDate,
            'addressfloorNumber' => $request->addressFloorNumber,
    'addressbuildingNumber' => $request->addressBuildingNumber,
    'addressstreet' => $request->addressStreet,
    'addresscity' => $request->addressCity,
    'addressCountry' => $request->addressCountry,
     'addressApartmentNumber' => $request->addressApartmentNumber,
    'disSign' => $request->disSign,
     'phonenum' => $request->phonenum,
        ]);

        event(new Registered($user));

        // $created_user = User::where('email', '=', $request->email)->first();

        return response()->json([
            'stus' => 'registered',
        ], 200);
    }
    
  
  

 public function saveCustomerToken(Request $request)
{
 
   
   
   $request->validate([
            'user_id'           => 'required|integer',
            'notification_token'=> 'required|string',
        ]);

   
        $customerInfo = customer_info::where('user_id', $request->user_id)->first();

        if (!$customerInfo) {
            return response()->json([
                'message' => 'Customer info not found.',
            ], 404);
        }
   $customerInfo->notification_token =  $request->notification_token;
   $customerInfo->save();


        return response()->json([
            'message' => 'Notification token saved successfully.',
            'data'    => $customerInfo->only(['user_id','notification_token']),
        ], 200);
}

   

  
  
  
  
  
    public function login(Request $request)
    {


        if (!Auth::attempt($request->only("email", "password"))) {
            return response()->json(
                [
                    "user" => Null,
                    "message" => "invalid-credentials",
                    "stus" => "failed",
                ],
                422
            );
        }




        $user = User::where("email", $request["email"])->firstOrFail();

        $user_loggedin = [
            'id' => $user->id,
            'email' => $user->email,
            'stus' => 'loggedin'
        ];

        return response()->json(
            $user_loggedin,
            200
        );
    }
  
  

  
    public function reset_pass(Request $request)
    {
        $user = User::where("email", $request["email"])->first();
        if ($user) {
            $user->password = Hash::make($request["new_password"]);
            $user->save();
            return response()->json([
                'stus' => 'reset',
            ], 200);
        } else {
            return response()->json([
                'stus' => 'failed',
            ], 200);
        }
    }
 
    public function update_user_data(Request $request)
    {



        $verify_user =  User::find($request->query('user_id'));

        if ($verify_user) {

            $user_info = customer_info::where("user_id", $request->query('user_id'))->firstOrFail();

            $user_info->update($request->all());

            return response()->json(

                [
                    "data" => $user_info,
                    "message" => "custmer data has been updated",
                    "stus" => "success",
                ],
                200
            );
        }

        return response()->json(
            [
                "user" => [],
                "message" => "invalid-credentials",
                "stus" => "failed",
            ],
            200
        );
    }
    public function get_user_data(Request $request)
    {

        $verify_user =  User::find($request->query('user_id'));
        // dd($verify_user);


        if ($verify_user->id ?? null) {


            $user_info = customer_info::where('user_id', $verify_user->id)->get(); // error
            if (count($user_info)) {
                return new userDataResource($user_info[0]);
            }

            return response()->json(
                [
                    "user" => [],
                    "message" => "user not found",
                    "stus" => "failed",
                    "code" => 404,
                ],
                404
            );
        }

        return response()->json(
            [
                "user" => [],
                "message" => "user not found",
                "stus" => "failed",
                "code" => 404,
            ],
            404
        );
    }


    public function CompanyData(Request $request)
    {


        return response()->json(
            [
                "data" => [
                    "phone_number_1" => env('PHONENOONE'),
                    "phone_number_2" => env('PHONENOTWO'),
                    "email" => env('EMAIL'),
                ],
                "message" => "data fetched correctrly",
                "stus" => "success",
                "code" => 200,
            ],
            200
        );
    }



    public function send_user_photo(Request $request)
    {
        $user = User::find($request->query('user_id'));
        $user_info = customer_info::where("user_id", $request->query('user_id'))->firstOrFail();


        // dd($user_info);
        // dd('hello');
        if ($user && $user_info) {

            if (isset($user_info->profileImage)) {
                $image_path = asset('uploads/' . $user_info->profileImage);  // Value is not URL but directory file path
                if (File::exists($image_path)) {
                    File::delete($image_path);
                }
            }


            $request->photo->storeAs('profile_photo', 'user_' . $user->id . '.jpg', 'my_public');



            $user_info->profileImage = 'user_' . $user->id . '.jpg';
            $user_info->update();

            return response()->json(

                [
                    "message" => "user profile photo  has been updated successfully",
                    "stus" => "success",
                    "code" => 200,
                ],
                200
            );
        } else {
            return response()->json(
                [
                    "user" => [],
                    "message" => "error has acuured",
                    "stus" => "failed",
                ],
                500
            );
        }
    }
}
