<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use Tests\TestCase;

class LeaderBoardTest extends TestCase
{
    /**
     * Test registration api with missing fields
     */
    public function testRequiredFieldsInUserRegistration()
    {
        // Missing registration field.
        // Name field is missing in payload
        $payload = [
            'age' => 20,
            'points' => 12,
            'address' => 'Test Address'
        ];
        $this->json(
            'POST',
            'api/users/register',
            $payload,
            ['Accept' => 'application/json']
        )
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJson([
                "status" => false,
                "error" => "The given data was invalid.",
                "message" => "There was an error in your request."
            ]);

    }

    /**
     * Test Delete user with invalid user ID
     */
    public function testDeleteUserWithInvalidId()
    {
        // User ID is invalid
        $userID = 'TEST';
        $this->json(
            'DELETE',
            'api/users/delete/' . $userID,
            ['Accept' => 'application/json']
        )
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
                "message" => "User cannot be found!"
            ]);
    }
}
