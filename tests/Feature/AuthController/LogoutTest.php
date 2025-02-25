<?php

use App\Models\User;

test('user can logout', function () {
    $user = User::factory()->create();

    $token = $user->createToken('test-token')->plainTextToken;

    $this->withHeader('Authorization', "Bearer $token")
        ->post('/api/logout')
        ->assertOk();
});
