<?php

use App\Models\User;
use App\Models\Series;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it creates a visit', function () {
    $series = Series::factory()->create();
    $series->visit();
    expect($series->visits->count())->toBe(1);
});

test('it creates a visit with the default ip address', function () {
    $series = Series::factory()->create();
    $series->visit()->withIp();
    expect($series->visits->first()->data)->toMatchArray(['ip' => request()->ip()]);
});

test('it creates a visit with the give ip address', function () {
    $series = Series::factory()->create();
    $series->visit()->withIp('cats');
    expect($series->visits->first()->data)->toMatchArray(['ip' => 'cats']);
});

test('it creates a visit with the custom data', function () {
    $series = Series::factory()->create();
    $series->visit()->withData([
        'cats' => true
    ]);
    expect($series->visits->first()->data)->toMatchArray(['cats' => true]);
});

test('it creates a visit with a default user', function () {
    $this->actingAs($user = User::factory()->create());
    $series = Series::factory()->create();
    $series->visit()->withUser();
    expect($series->visits->first()->data)->toMatchArray(['user_id' => $user->id]);
});


test('it creates a visit with a given user', function () {
    $user = User::factory()->create();
    $series = Series::factory()->create();
    $series->visit()->withUser($user);
    expect($series->visits->first()->data)->toMatchArray(['user_id' => $user->id]);
});

test('it does not create duplicate visits with the same data', function () {
    $series = Series::factory()->create();
    $series->visit()->withData([
        'cats' => true
    ]);

    $series->visit()->withData([
        'cats' => true
    ]);
    expect($series->visits->count())->toBe(1);
});