# Nova Combobox Filter


[![Latest Version on Packagist](https://img.shields.io/packagist/v/harrald/nova-combobox-filter.svg?style=flat-square)](https://packagist.org/packages/harrald/nova-combobox-filter)
[![Total Downloads](https://img.shields.io/packagist/php-v/harrald/nova-combobox-filter.svg?style=flat-square)](https://packagist.org/packages/harrald/nova-combobox-filter)
[![Total Downloads](https://img.shields.io/packagist/dt/harrald/nova-combobox-filter.svg?style=flat-square)](https://packagist.org/packages/harrald/nova-combobox-filter)
[![Total Downloads](https://img.shields.io/packagist/l/harrald/nova-combobox-filter.svg?style=flat-square)](https://packagist.org/packages/harrald/nova-combobox-filter)


This component is based on the [Headless UI Combobox component](https://headlessui.dev/vue/combobox) and adds a multiselect filter to Laravel Nova. 

## Requirements
- `php: >=7.3`
- `laravel/nova: ^4.0`

## Installation

Install via Composer:

```bash
composer require harrald/nova-combobox-filter
```

## Usage

The `nova-combobox-filter` extends the [Nova select filter](https://nova.laravel.com/docs/4.0/filters/defining-filters.html#select-filters). And thus follows the same logic as to how to implement it.

### Filter
Make a new Filter class in `App/Nova/Filters` and let it extend the `\Harrald\NovaComboboxFilter\NovaComboboxFilter` class. 

Use the following format:
```php
<?php

namespace App\Nova\Filters;

use Harrald\NovaComboboxFilter\NovaComboboxFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class OpenPaidStateFilter extends NovaComboboxFilter
{
    /**
     * The displayable name of the filter.
     *
     * @var string
     */
    public $name = 'State';

    /**
     * The name of the column to filter on
     */
    protected function columnName(): string
    {
        return 'state';
    }

    /**
     * Get the filter's available options.
     */
    public function options(Request $request): Collection
    {
        return Collection::make([
            'Open' => 'open',
            'Paid' => 'paid',
        ]);
    }
}
```

- The property `$name` is the displayable name of the filter.
- The method `columnName` must return the name of the column to filter on
- The `options` method should return a `Illuminate\Support\Collection` with key/value pairs.


With Dynamic options
```php
public function options(Request $request): Collection
{
    return User::all()->pluck('id', 'name');
}
```

### Resource
Use the new filter in you Resource. Follows the same logic as a any other [Nova filter](https://nova.laravel.com/docs/4.0/customization/filters.html#registering-filters)
```php
/**
 * Get the filters available for the resource.
 *
 * @param NovaRequest $request
 * @return array
 */
public function filters(NovaRequest $request): array
{
    return [
        OpenPaidStateFilter::make(),
    ];
}
```



## Screenshots
<img src="readme/img/multiselect.png" alt="multi options selected" title="multi options selected" width="285" height="380">

<img src="readme/img/typing.png" alt="filter options by typing" title="filter options by typing" width="285" height="380">


## Credits

This package was inspired by [optimistdigital/nova-multiselect-filter](https://github.com/optimistdigital/nova-multiselect-filter)

## License

This project is open-sourced software licensed under the MIT license.
