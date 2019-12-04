# search-repository

## Instruction

create a specific repository for your model (e.g User) and extend the SearchRepository. In your class you should define your filterable and searchable attributes and you are ready to go.

```php

use Waxwink\SearchRepository\SearchRepository;

class UserSearchRepository extends SearchRepository
{

    protected $searchable_attributes = [
        'name',
        'last_name',
        'cellphone',
    ];
    
    protected $filterable_attributes = [
        'birth_date',
        'gender',
        'name',
        'last_name',
        'cellphone',
        'email',
        'created_at'
    ];

    public function __construct()
    {
        $this->query = App\User::query();
    }
}
```

After creating the search repository class it can be used in your controller as follows while using the `SearchTrait`:

```php
use Waxwink\SearchRepository\Concerns\SearchTrait;
use Illuminate\Http\Request;

class UserController {
    use SearchTrait;
    
    public function index(Request $request, UserSearchRepository $userSearchRepository)
    {
        $users = $this->filterAndSearch($request, $userSearchRepository)->paginate();
        
        // ...
    }
}

```

## Request Body
The request body should contain the fields that are going to be filtered like the following:

```json

{
    "gender":["m"],
    "created_at":"2019-01-01",
    "birth_date":["1999-01-01", ">"],
    "name":["joe", "like"]
}
```

## Search Param
Search param should contain the searching key words like:

```
app/users?search=jack
```
