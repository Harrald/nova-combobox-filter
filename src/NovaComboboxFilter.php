<?php

namespace Harrald\NovaComboboxFilter;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

abstract class NovaComboboxFilter extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'nova-combobox-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param NovaRequest $request
     * @param  Builder  $query
     * @param  mixed  $value
     * @return Builder
     */
    public function apply(NovaRequest $request, $query, $value): Builder
    {
        return $query->whereIn($this->columnName(), $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function options(NovaRequest $request)
    {
        return [
            'Foo' => 'foo',
            'Bar' => 'bar',
        ];
    }

    abstract protected function columnName(): string;
}
