<?php
use App\Models\User;
use App\Models\Idea;


it('creates a new idea', function() {
    $user = \App\Models\User::factory()->create();

    // 1. Submit the complete multi-dimensional form payload straight to the route
    $response = $this->actingAs($user)
        ->post(route('ideas.store'), [
            'title'       => 'Some Example Idea',
            'status'      => 'completed',
            'description' => 'An example description',
            'links'       => ['http://example.com', 'http://google.com'],
            'steps'       => ['walk the dog', 'walk the walk'],
        ]);

    // 2. Assert successful form processing and redirect
    $response->assertRedirect(route('ideas.index'));
    
    // 3. Verify the main Idea record was successfully written to the database
    expect($idea = App\Models\Idea::first())->toMatchArray([
        'title'       => 'Some Example Idea',
        'status'      => 'completed',
        'description' => 'An example description',
        'links'       => ['http://example.com', 'http://google.com'],
    ]);

    // 4. Verify that the two steps were linked successfully via the relationship
    expect($idea->steps)->toHaveCount(2);
});



it('edits an existing new idea', function() {
    $this->actingAs($user = App\models\User::factory()->create());
    $idea = App\Models\Idea::factory()->for($user)->create();

    visit(route('ideas.show',$idea))
        ->click('@edit-idea-button');
});
