# Useful command and shortcuts for running, testing code, seeding db etc

## To migrate your db files

- php artisan migrate

## To seed the database based on the factory and seeds files

- php artisan seed:db

## To test te relation ship of your pivot table, for the many to many relationship between users table and another table

- php artisan tinker
- $user = \App\Models\User::find(1)
- $relation = $user->documents()
- $relation->attach(1)
- $relation->attach([2,3])
- $relation->detach(1)

## Test the mutator functions written in the Document model

- php artisan tinker
- \App\Models\Document::find(1)->title_upper_case

- $post = \App\Models\Document::find(1)
- $post->title = 'HEYYAAAAA'
- $post (var_dump s the value in the REPL shell)
