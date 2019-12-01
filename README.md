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
}
```

After creating the search repository class it can be used in your controller as follows while using the `SearchTrait`:

```php
use Waxwink\SearchRepository\Concerns\SearchTrait;

class UserController {
    use SearchTrait;
    
    public function index(Request $request, UserSearchRepository $userSearchRepository)
    {
        $users = $this->filterAndSearch($request, $userSearchRepository)->paginate();
        ...
    }
}

```