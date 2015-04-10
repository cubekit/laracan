# laracan

## Installation

- Require the package with composer:

`composer require cubekit/laracan`

- Add provider to your `config/app.php`:

```

	'providers' => [

	    // ...

        'Cubekit\Laracan\LaracanServiceProvider',

        // ...

	],

```

- Publish config:

`php artisan vendor:publish --provider="Cubekit\Laracan\LaracanServiceProvider"`

- Add the `Ability` class to the `app` folder and implement the `Cubekit\Laracan\AbilityContract`

> Note: the default config assumes that the `Ability` class is placed in the `app` folder. You are free to change it and place the class where would you want.

## Usage

- Define abilities

```php
class Ability implements AbilityContract {

    public function initialize($user, Closure $can)
    {
        $user = $user ?: new App\User;

        // NOTE: Laracan does not provide any roles behavior! Assume that some
        // package already installed for this, like Entrust
        if ($user->hasRole('admin')) {

            // Admin can edit posts and comments unconditionally
            $can('edit', 'Post');
            $can('edit', 'Comment');

            return;
        }

        // User can edit a post only if he is its author
        $can('edit', 'Post', ['author_id' => $user->getKey()]);

        $can('edit', 'Comment', function($comment) use ($user)
        {
            // User can edit a comment only if he is its author
            // and comment is not older than 15 minutes
            return (
                $comment->author_id == $user->getKey() &&
                $comment->created_at >= Carbon::now()->subMinutes(15)
            );
        });

    }
}
```

- Check ability in a request

```php
class EditPostRequest {

    public function rules()
    {
        // ...
    }

    public function authorize()
    {
        $post = Post::find( $this->route('post') );

        return can('edit', $post);
    }

}
```

- Check ability in a view

```php
@foreach($post->comments as $comment)

<div class="comment">

    <div class="comment-body">{{ $comment->body }}</div>

    @can('edit', $comment)

        <div class="comment-footer">
            <a href="{{ route('comment.edit', $comment) }}">Edit</a>
        </div>

    @endcan

    </div>

</div>

@endforeach
```

- Or you can use the `can` function directly to force IDE understand this code

```php
@foreach($post->comments as $comment)

<div class="comment">

    <div class="comment-body">{{ $comment->body }}</div>

    @if( can('edit', $comment) )

        <div class="comment-footer">
            <a href="{{ route('comment.edit', $comment) }}">Edit</a>
        </div>

    @endif

    </div>

</div>

@endforeach
```

## License

MIT