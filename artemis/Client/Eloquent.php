<?php


namespace Artemis\Client;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;


/**
 * Class Eloquent
 * @package Artemis\Client
 *
 * @method static mixed|static find($id, $columns = ['*']) Execute a query for a single record by ID.
 * @method static inRandomOrder($seed = '') Put the query's results in random order.
 * @method static Builder select($columns = ['*']) Set the columns to be selected.
 * @method static Builder selectRaw($expression, array $bindings = []) Add a new "raw" select expression to the query.
 * @method static EloquentBuilder where($column, $operator = null, $value = null, $boolean = 'and') Add a basic where clause to the query.
 */
abstract class Eloquent extends Model
{

}