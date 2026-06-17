<?php

use App\Models\Idea;
use Illuminate\Database\Eloquent\Collection;

test("it belongs to a user", function () {
    $idea = Idea::factory()->create();

    expect($idea->user)->toBeInstanceOf(App\Models\User::class);
});


test("it can have steps", function () {
    $idea = Idea::factory()->create();

    expect($idea->steps)->toBeEmpty();

    $idea->steps()->create([
        'description' => 'Do the thing',
    ]);

    // Refresh the idea to get the updated steps
    $idea->refresh(); 

    expect($idea->steps)->toHaveCount(1);
});