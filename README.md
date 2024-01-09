# Model Logger

Tracking any changes to model into its own table.

## Table Of Contents

- [Model Logger](#model-logger)
  - [Table Of Contents](#table-of-contents)
  - [Installation](#installation)
  - [Usage](#usage)
  - [Gotchas](#gotchas)
    - [Relationship](#relationship)
    - [User Id](#user-id)
    - [What we cannot track](#what-we-cannot-track)

## Installation

install the package

```bash
composer require dekate/model-logger
```

publish config and migration

```bash
php artisan vendor:publish --tag=model-logger
```

## Usage

use `Dekate\ModelLogger\LogModel` Trait into your model

ex.

```php
<?php

namespace App\Models;

use Dekate\ModelLogger\LogModel;
use Illuminate\Database\Eloquent\Model;

class MyModel extends Model
{
  use LogModel;

  /** The rest of the code */

}
```

To disable log for specific operation in a single model.
Add `protected $disableLog` to your model.
Valid values are string composed of `C` (create), `U` (update), and `D` (delete).

```php
class YourModel extends Model
{
  use LogModel;

  // disable Create log
  protected $disableLog = "C";
}
```

```php
class YourModel extends Model
{
  use LogModel;

  // disable Create Delete
  protected $disableLog = "CD";
}
```

## Gotchas

### Relationship

The `sync()` operation in a relation cannot be tracked with this trait.
Thus, we're providing `syncWithRelation` as an alternative.

```php
/** You can change */
$myModel->myRelation()->sync([])

/** To */
$myModel->syncWithRelation('myRelation', []);
```

### User Id

Responsible user for the changes are tracked with `auth()->id()`.
So, we're unable to track the user if `auth` it's empty or not being used.

### What we cannot track

- Anything with mass operation _except `sync` as explained above_ such as `insert` or `delete` with query builder.
- Any changes with raw queries
