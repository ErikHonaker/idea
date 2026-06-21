<?php

use App\models\Idea;
use App\models\User;

it('requires authentication', function () {
    $idea = Idea::factory()->create();

    $this->get(route('ideas.show', $idea))->assertRedirecttoRoute('login');
});

it('disallows accessing an idea you did not create', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $idea = Idea::factory()->create();

    $this->get(route('ideas.show', $idea))->assertForbidden();
});
