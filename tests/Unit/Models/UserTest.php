<?php

use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('to array', function () {
    $user = User::factory()->create()->fresh();

    expect(array_keys($user->toArray()))->toBe([
        'id',
        'name',
        'email',
        'email_verified_at',
        'created_at',
        'updated_at',
    ]);
});

it('can be created', function () {
    $user = User::factory()->create();
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
    ]);
});

it('has preferences', function () {
    $user = User::factory()->create();
    $preferences = UserPreference::factory()->create(['user_id' => $user->id]);

    expect((int) $user->preferences->id)->toBe((int) $preferences->id);
});
