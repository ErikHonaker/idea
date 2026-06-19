<?php



it('creates a new idea', function() {
    $this->actingAs(\App\Models\User::factory()->create());

    visit('/ideas')
        ->click('@create-idea-button')
        ->fill('title', 'Some Example Idea')
        ->click('@button-status-completed')
        ->fill('description', 'An example description')
        ->fill('@new-link', 'http://example.com')
        ->click('@submit-new-link-button')
        ->fill('@new-link', 'http://google.com')
        ->click('@submit-new-link-button')
        ->click('Create')
        ->assertPathIs('/ideas');
    
    expect(App\Models\Idea::first())->toMatchArray([
        'title' => 'Some Example Idea',
        'status' => 'completed',
        'description' => 'An example description',
        'links' => ['http://example.com', 'http://google.com']
    ]);

});