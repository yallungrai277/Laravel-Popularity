<?php

use Carbon\Carbon;
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

test('it does not create a visit within the timeframe', function () {
    $series = Series::factory()->create();

    Carbon::setTestNow(now()->subDays(2));
    $series->visit();
    Carbon::setTestNow();
    $series->visit();
    $series->visit();

    expect($series->visits->count())->toBe(2);
});


test('it creates visit after a defaily daily timeframe', function () {
    $series = Series::factory()->create();
    $series->visit()->withIp();

    Carbon::setTestNow(now()->addDay()->addHour());
    $series->visit()->withIp();

    expect($series->visits->count())->toBe(2);
});

test('it creates visit after a hourly timeframe', function () {
    $series = Series::factory()->create();
    $series->visit()->hourlyInterval()->withIp();

    Carbon::setTestNow(now()->addHour()->addMinute());
    $series->visit()->hourlyInterval()->withIp();

    expect($series->visits->count())->toBe(2);
});

test('it creates visit after a daily timeframe', function () {
    $series = Series::factory()->create();
    $series->visit()->dailyInterval()->withIp();

    Carbon::setTestNow(now()->addDay());
    $series->visit()->dailyInterval()->withIp();

    expect($series->visits->count())->toBe(2);
});

test('it creates visit after a weekly timeframe', function () {
    $series = Series::factory()->create();
    $series->visit()->weeklyInterval()->withIp();

    Carbon::setTestNow(now()->addWeek());
    $series->visit()->weeklyInterval()->withIp();

    expect($series->visits->count())->toBe(2);
});

test('it creates visit after a monthly timeframe', function () {
    $series = Series::factory()->create();
    $series->visit()->monthlyInterval()->withIp();

    Carbon::setTestNow(now()->addMonth());
    $series->visit()->monthlyInterval()->withIp();

    expect($series->visits->count())->toBe(2);
});

test('it creates visit after a custom timeframe', function () {
    $series = Series::factory()->create();
    $series->visit()->customInterval(now()->subYear())->withIp();

    Carbon::setTestNow(now()->addYear());
    $series->visit()->customInterval(now()->subYear())->withIp();

    expect($series->visits->count())->toBe(2);
});