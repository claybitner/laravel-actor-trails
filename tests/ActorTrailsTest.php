<?php

use DigitalIndoorsmen\LaravelActorTrails\Traits\HasActorTrails;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    // Create a test users table
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->string('email')->nullable();
        $table->string('password')->nullable();
        $table->timestamps();
    });

    // Create a test posts table
    Schema::create('posts', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->json('created_by')->nullable();
        $table->json('modified_by')->nullable();
        $table->json('deleted_by')->nullable();
        $table->softDeletes();
        $table->timestamps();
    });
});

afterEach(function () {
    Schema::dropIfExists('posts');
    Schema::dropIfExists('users');
});

// Dummy User model
class TestUser extends Authenticatable
{
    protected $table = 'users';

    protected $guarded = [];
}

// Dummy Post model with HasActorTrails
class TestPost extends Model
{
    use HasActorTrails, \Illuminate\Database\Eloquent\SoftDeletes;

    protected $table = 'posts';

    protected $guarded = [];

    protected $casts = [
        'created_by' => 'array',
        'modified_by' => 'array',
        'deleted_by' => 'array',
    ];
}

it('stores created_by when creating a model', function () {
    $user = TestUser::create(['name' => 'Alice']);
    Auth::login($user);

    $post = TestPost::create(['title' => 'Hello World']);

    expect($post->created_by)
        ->toBeArray()
        ->and($post->created_by['id'])->toEqual($user->id)
        ->and($post->created_by['display_name'])->toEqual('Alice');
});

it('stores modified_by when updating a model', function () {
    $user = TestUser::create(['name' => 'Bob']);
    Auth::login($user);

    $post = TestPost::create(['title' => 'Original']);
    $post->update(['title' => 'Updated']);

    expect($post->modified_by)
        ->toBeArray()
        ->and($post->modified_by['id'])->toEqual($user->id)
        ->and($post->modified_by['display_name'])->toEqual('Bob');
});

it('stores deleted_by when soft deleting a model', function () {
    $user = TestUser::create(['name' => 'Charlie']);
    Auth::login($user);

    $post = TestPost::create(['title' => 'To be deleted']);
    $post->delete();

    $post->refresh();

    expect($post->deleted_by)
        ->toBeArray()
        ->and($post->deleted_by['id'])->toEqual($user->id)
        ->and($post->deleted_by['display_name'])->toEqual('Charlie');
});
