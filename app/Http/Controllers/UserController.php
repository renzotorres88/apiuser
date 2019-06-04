<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\URL;
use Psr\Log\LoggerInterface;

class UserController extends Controller
{
    private $logger;

    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * Function that add an user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addUser(Request $request)
    {
        $validationRules;

        if (isset($request->image)) {
            $validationRules['image'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
        }
        $validationRules['name'] = 'required';
        $validationRules['email'] = 'required|unique:users';
        
        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            $this->logger->error('addUser function - Fail validation: ' . $validator->messages());

            return response()->json([
                'message' => $validator->messages()
            ], 422);
        } else {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            $this->logger->info('User added succesfully. User id: ' . $user->id);
        }

        if (isset($request->image)) {
            $this->saveImage($user, $request->file('image'));
        };

        return response()->json([
            'message' => 'User added successfully',
            'user data' => $user->getAttributes()
        ], 201);
    }

    /**
     * Function that return the User data
     *
     * @param [type] $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserData($userId)
    {
        $user = User::find($userId);

        if ($user) {
            $this->logger->info('Found and returned user. User Id: ' . $userId);

            return response()->json([
                'User data' => $user->getAttributes()
            ], 200);
        } else {
            $this->logger->info('User not found and \'User not found\' notice returned. Id not found: ' . $userId);

            return response()->json([
                'message' => 'User not found.'
            ], 404);
        }
    }

    /**
     * Function that update the User data
     *
     * @param Request $request
     * @param [type] $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUserData(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            $this->logger->info('User not found and \'User not found\' notice returned. Id not found: ' . $userId);

            return response()->json([
                'message' => 'User not found.'
            ], 404);  
        } 

        $validationRules;
        $validationRules['name'] = 'required';

        if ($request->email !== $user->email) {
            $validationRules['email'] = 'required|unique:users';
        }
        
        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            $this->logger->error('updateUserData function - Fail validation: ' . $validator->messages());

            return response()->json([
                'message' => $validator->messages()
            ], 422);
        } else {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();  

            $this->logger->info('User updated succesfully. User id: ' . $user->id);
        }

        return response()->json([
            'message' => 'User data updated successfully.'
        ], 201);
    }

    /**
     * Function that delete a User
     *
     * @param [type] $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUser($userId)
    {
        $user = User::find($userId);

        if ($user) {
            $user->delete();

            $this->logger->info('User deleted succesfully. User id: ' . $userId);
        } else {
            $this->logger->info('User not found and \'User not found\' notice returned. Id not found: ' . $userId);

            return response()->json([
                'message' => 'User not found.'
            ], 404); 
        }

        return response()->json([
            'message' => 'User deleted successfully.'
        ], 200);
    }

    /**
     * Function that upload an User image
     *
     * @param Request $request
     * @param [type] $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadUserImage(Request $request, $userId)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            $this->logger->error('uploadUserImage function - Fail validation: ' . $validator->messages());

            return response()->json([
                'message' => $validator->messages()
            ], 422);
        }

        $user = User::find($userId);
        
        if ($user) {
            $this->saveImage($user, $request->file('image'));
        } else {
            $this->logger->info('User not found and \'User not found\' notice returned. Id not found: ' . $userId);

            return response()->json([
                'message' => 'User not found.'
            ], 404);
        }
        
        return response()->json([
            'message' => 'Image upload successfully',
            'url' => $user->image
        ], 201);
    }

    /**
     * Function that save the image
     *
     * @param [type] $user
     * @param [type] $image
     * @return void
     */
    private function saveImage($user, $image)
    {
        $imgName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images'), $imgName);
        $user->image = URL::to('/') . '/images/' . $imgName;
        $user->save();

        $this->logger->info('User image uploaded successfully. User Id: ' . $user->id);
    }
}
