<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{

        /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','getUsersWithSectors','getUserProfileData']]);
    }


    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'string',
            'image' => 'image|nullable',
            'isAdmin' => 'boolean',
            'position' => 'string',
            'sector-id'=> 'required'
        ]);



        if ($validator->fails()) {
            return response()->json([
                "status_code" => 422,
                //"error" => "تأكد من صحة البيانات المدخله وحاول مره اخري"
                "error" => $validator->errors()
            ],
            422);
        }

        $image = "";
        $filename = "";

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = $image->store('images');
        }
        $user = User::create(array_merge(
                    $validator->validated(),
                    [
                        'password' => bcrypt($request->password),
                        'image' => basename($filename),
                    ]
                ));
        return response()->json([
            'message' => 'تم تسجيل المستخدم بنجاح',
            "status_code" => 200,
        ], 200);

}


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status_code" => 422,
                "error" => "تأكد من صحة كلمة المرور والبريد الالكتروني وحاول مره اخري"
            ], 422);
        }
        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json([
                'status_code' => 401,
                'error' => 'هذا الحساب غير مسجل من قبل'
            ], 401);
        }
        return $this->createNewToken($token);
    }




//     function getCurrentAccessToken()
// {
//     $accessToken = Auth::user()->token();

//     if ($accessToken) {
//         return response()->json([
//             'current_token' => $accessToken->accessToken,
//             'status_code' => 200
//         ]);
//     }

//     return response()->json([
//         'current_token' => null,
//         'status_code' => 200
//     ]);;
// }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        try {
            return response()->json(auth()->user());
        } catch (\Exception $e) {
            //Log::error('An error occurred: ' . $e->getMessage());
            return response()->json([
                "status_code" => 422,
                "error" => "حدث خطأ ما"
            ],
            422);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        // Assuming your user model is named "User"
        $user = User::find($user->id);
        if(!$user){
            return $this ->apiResponse(null,'user not found, try again',404);
        }

        $fields = $request->only(['name', 'email', 'password', 'phoneNumber', 'imgSrc','departmentId']); // Add additional fields here

        foreach ($fields as $field => $value) {
            if (!empty($value)) {
                $user->$field = $value;
            }
        }

        // Validate and update the user's profile details
        // Add other profile fields here
        $user->save();

        return response()->json(['message' => 'Profile updated successfully']);
    }




    public function getUsersWithSectors(Request $request)
    {
        try {
            $users = DB::table('users')
                ->join('sector', 'users.sector-id', '=', 'sector.sector_id')
                ->select('users.name', 'users.id','users.image' , 'sector.sector_name as sector_name')
                ->get();

            return response()->json([
                'users' => $users,
                'status_code' => 200
            ]);
        } catch (\Exception $e) {
        return response()->json([
            "status_code" => 500,
            "error" => "An error occurred $e"
        ],
        500);

        }
    }



    public function getUserProfileData(Request $request){

        $id = $request->input('id');
        $userProfile = User::where('id', $id)-> first();

        return response()->json([
            'userProfile' => $userProfile,
            'status_code' => 200
        ]);

    }


}
