<?php

test('new users can register', function () {
    $this->post('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertOk()
        ->assertJsonStructure([
            'data' => [
                'token',
                'user',
            ],
        ]);
});
