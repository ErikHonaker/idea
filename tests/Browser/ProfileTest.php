<?php

use App\Models\User;
use App\Notifications\EmailChanged;

it('requires authentication', function () {
    $this->get(route('profile.edit'))->assertRedirect('/login');
});
it('edits a user profile', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    visit(route('profile.edit'))
        ->assertValue('name', $user->name)
        ->fill('name', 'John Doe')
        ->assertValue('email', $user->email)
        ->fill('email', 'john@example.com')
        ->press('Update Account')
        ->assertSee('Profile updated');

    expect($user->fresh())->toMatchArray([
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);
});

it('notifies the original email if updated', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Notification::fake();

    $originalEmail = $user->email;
    visit(route('profile.edit'))
        ->assertValue('name', $user->name)
        ->fill('email', 'john@example.com')
        ->press('Update Account')
        ->assertSee('Profile updated');

    Notification::assertSentOnDemand(EmailChanged::class, fn (EmailChanged $notification, $routes, $notifiable) => $notifiable->routes['mail'] === $originalEmail);

});
