<?php
use Acme\Demo\ORM\Entities\Comment;
use Acme\Demo\ORM\Entities\Post;
use Acme\Demo\ORM\Entities\Profile;
use Acme\Demo\ORM\Entities\Role;
use Acme\Demo\ORM\Entities\User;
use Acme\Demo\ORM\Entities\Usertype;
use Maduser\Minimal\Database\Connectors\PDO;
use Maduser\Minimal\Facades\Config;

/** @var \Maduser\Minimal\Routers\Router $route */

$route->get('orm', function () {

    PDO::connection(Config::item('database'));

    // Getting instances
    $user = User::create();
    $type = Usertype::create();
    $profile = Profile::create();
    $role = Role::create();
    $post = Post::create();
    $comment = Comment::create();

    // Truncating tables
    $user->truncate();
    $type->truncate();
    $profile->truncate();
    $role->truncate();
    $post->truncate();
    $comment->truncate();

    // Creating records
    for ($i = 1; $i <= 10; $i++) {

        $user = User::create();
        $user->username = 'user-' . $i;
        $user->save();

        $type = Usertype::create();
        $type->name = 'type-' . $i;
        $type->save();

        $profile = Profile::create();
        $profile->firstname = 'profile-firstname-' . $i;
        $profile->lastname = 'profile-lastname-' . $i;
        $profile->save();

        $role = Role::create();
        $role->name = 'role-' . $i;
        $role->save();

        $post = Post::create();
        $post->title = 'post-title-' . $i;
        $post->text = 'post-text-' . $i;
        $post->save();

        $comment = Comment::create();
        $comment->title = 'comment-title-' . $i;
        $comment->text = 'comment-text-' . $i;
        $comment->save();
    }

    // Quick find
    $user = User::find(1);

    // Updating rows
    $i = 0;
    $collection = $user->getAll();
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
    $collection = Role::create()->getAll();

    $user->roles()->attach($collection);

    $collection = Role::create()->where(
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
