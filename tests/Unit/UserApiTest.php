<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Illuminate\Support\Facades\DB;

class UserApiTest extends TestCase
{
     /**
     * Validate the user creation
     *
     * @group userAdd
     */
    public function testAddUserFunction() {
        //Truncate users table
        DB::table('users')->truncate();

        //User data to request
        $userData = [
            'name' => 'Renzo Torres',
            'email' => 'renzo@test.com'
        ];
        
        //Execute post request to save a new user
        $response = $this->post('/api/add-user', $userData);

        //Find user to validation
        $user = User::select('id')->where('email', 'renzo@test.com')->first();

        //Validation response
        $response->assertStatus(201)
            ->assertSee('"name":"Renzo Torres","email":"renzo@test.com"');
    }

    /**
     * Validate that name is required field and email is unique field
     *
     * @group addUserWrong
     */
    public function testAddUserWithoutNameAndEmailExisting() {
        //Truncate users table
        DB::table('users')->truncate();

        //User data to request
        $userData = [
            'name' => 'Renzo Torres',
            'email' => 'renzo@test.com'
        ];
        $firstResponse = $this->post('/api/add-user', $userData);

        //Validate the correct user creation
        $firstResponse->assertStatus(201);

        $wrongUserData = [
            'email' => 'renzo@test.com'
        ];
        $response = $this->post('/api/add-user', $wrongUserData);

        $response->assertStatus(422)
            ->assertSee('The name field is required')
            ->assertSee('The email has already been taken');
    }

    /**
     * Validate that function returns the user data
     *
     * @group getUserData
     */
    public function testGetUserDataFunction() {
        //Truncate users table
        DB::table('users')->truncate();

        //User data to request
        $userData = [
            'name' => 'Renzo F. Torres',
            'email' => 'renzo@test.com'
        ];
        
        //Execute post request to save a new user
        $response = $this->post('/api/add-user', $userData);

        //Find user to validation
        $user = User::select('id')->where('email', 'renzo@test.com')->first();

        //Execute request
        $response = $this->get('/api/get-user-data/' . $user->id);

        $response->assertStatus(200)
            ->assertSee('"name":"Renzo F. Torres","email":"renzo@test.com"');
    }

    /**
     * Validate the update user data
     *
     * @group updateUserData
     */
    public function testUpdateUserDataFunction() {
        //Truncate users table
        DB::table('users')->truncate();

        //User data to request
        $userData = [
            'name' => 'Renzo Torres',
            'email' => 'renzo@test.com'
        ];
        $firstResponse = $this->post('/api/add-user', $userData);

        //Validate the correct user creation
        $firstResponse->assertStatus(201);

        //Find user to validation
        $user = User::select('id')->where('email', 'renzo@test.com')->first();

        $updateUserData = [
            'name' => 'Renzo Torres Update',
            'email' => 'new_email@test.com'
        ];
        $response = $this->put('/api/update-user-data/' . $user->id, $updateUserData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'User data updated successfully.'
            ]);
    }

    /**
     * Validate the user destroy
     *
     * @group deleteUser
     */
    public function testDeleteUserFunction() {
        //Truncate users table
        DB::table('users')->truncate();

        //User data to request
        $userData = [
            'name' => 'Renzo Torres',
            'email' => 'renzo@test.com'
        ];
        $firstResponse = $this->post('/api/add-user', $userData);

        //Validate the correct user creation
        $firstResponse->assertStatus(201);

        //Find user to validation
        $user = User::select('id')->where('email', 'renzo@test.com')->first();

        $response = $this->delete('/api/delete-user/' . $user->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User deleted successfully.'
            ]);
    }
}
