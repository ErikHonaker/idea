<?php

use App\Models\User;


test('logs in a user', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
    ]);

    visit('/login')
        ->fill('email', 'john@example.com')
        ->fill('password', 'password123')
        ->click('@login-button')
        ->assertPathIs('/');
    
    $this->assertAuthenticated();

});


test('logs out a user', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    visit('/')->click('Log out');
    
    $this->assertGuest();

});