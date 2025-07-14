<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Auth\User;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_testing_creation()
    {
        // create a fake organization
        $user = User::factory()->create();
        $this->actingAs($user);
        //Post request to create a new book
        $response = $this->post('/testing', [
            'customer_name' => 'New name',
            'phone' => 'New phone',
        ]);
        //Check if the book was created
        $response->assertRedirect('/testing');
        $this->assertDatabaseHas('testing', [
            'customer_name' => 'New Name',
            'phone' => 'New Phone',
        ]);
    }
}
