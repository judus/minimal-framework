<?php
use Acme\Demo\ORM\Entities\Comment;
use Acme\Demo\ORM\Entities\Post;
use Acme\Demo\ORM\Entities\Profile;
use Acme\Demo\ORM\Entities\Role;
use Acme\Demo\ORM\Entities\User;
use Acme\Demo\ORM\Entities\Usertype;
use Maduser\Minimal\Database\Connectors\PDO;
use Maduser\Minimal\Facades\Config;

/** @var \Maduser\Minimal\Routers\Router $router */

$router->get('orm', function () {

    PDO::connection(Config::item('database'));

    // Getting instances
    $user = User::instance();
    $type = Usertype::instance();
    $profile = Profile::instance();
    $role = Role::instance();
    $post = Post::instance();
    $comment = Comment::instance();

    // Truncating tables
    $user->truncate();
    $type->truncate();
    $profile->truncate();
    $role->truncate();
    $post->truncate();
    $comment->truncate();

    // Creating records
    for ($i = 1; $i <= 10; $i++) {

        $user = User::create(['username' => 'user-' . $i]);

        $type = Usertype::create(['name' => 'type-' . $i]);

        $role = Role::create(['name' => 'role-' . $i]);

        $profile = Profile::create([
            'firstname' => 'profile-firstname-' . $i,
            'lastname' => 'profile-lastname-' . $i
        ]);

        $post = Post::create([
            'title' => 'post-title-' . $i,
            'text' => 'post-text-' . $i
        ]);

        $comment = Comment::create([
            'title' => 'comment-title-' . $i,
            'text' => 'comment-text-' . $i
        ]);
    }

    // Quick find
    $user = User::find(1);

    // Updating rows
    $i = 0;
    $collection = User::all();
    foreach ($collection as $user) {
        $user->username = 'user-username-' . $i++;
        $user->save();
    }

    // Deleting rows
    $collection = $user->where(['id', '>', 5])->getAll();

    if ($collection) {
        foreach ($collection as $user) {
            $user->delete();
        }
    }

    // Retrieving related object
    $user->profile; // has one : ORM
    $user->type; // belongs to : ORM
    $user->posts; // has many : Collection
    $user->roles; // belongs to many : Collection

    // Attaching/detaching many to many relationships
    $user = User::find(1);
    $collection = Role::all();

    $user->roles()->attach($collection);

    $collection = Role::instance()->where(
        ['id', '<', 3],
        ['id', '>', '7', 'OR']
    )->getAll();

    $user->roles()->detach($collection);

    // Associate/Dissociate belongs to relationships
    $post = Post::find(1);
    $comment1 = Comment::find(1);
    $comment2 = Comment::find(2);
    $comment3 = Comment::find(3);

    $comment1->post()->associate($post);
    //d($comment);

    $comment2->post()->associate($post);
    //d($comment);

    $comment3->post()->associate($post);
    //d($comment);

    $comment2->post()->dissociate();

    //d($comment);

    // Eager loading relationships
    $user->with(['type', 'profile', 'roles', 'posts', 'comments'])->getAll();

    return count(PDO::getExecutedQueries()) . ' queries have been executed.';
});
