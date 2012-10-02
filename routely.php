<?php namespace Flare;

use \Route;
use \Str;

/**
 * A Laravel bundle to help create restful routes
 *
 * @package     Bundles
 * @subpackage  Routes
 * @author      JonoB
 * @version 	1.0.0
 *
 * @see http://github.com/JonoB/flare-routely
 */
class Routely
{
    protected $name = '';
    protected $singular = '';

    protected $parent = '';
    protected $parent_singular = '';

    protected $template = array(
        array(
            'method' => 'get',
            'route' => '/(:any)/edit',
            'as' => ':singular_edit',
            'uses' => ':name@edit',
        ),
        array(
        	'method' => 'get',
            'route' => '/(:any)/delete',
            'as' => ':singular_delete',
            'uses' => ':name@delete',
       	),
        array(
            'method' => 'get',
            'route' => '/add',
            'as' => ':singular_add',
            'uses' => ':name@add',
        ),
        array(
            'method' => 'get',
            'route' => '/(:any)',
            'as' => ':singular',
            'uses' => ':name@item',
        ),
        array(
            'method' => 'get',
            'route' => '',
            'as' => ':name',
            'uses' => ':name@index',
        ),
        array(
            'method' => 'post',
            'route' => '',
            'uses' => ':name@item',
        ),
        array(
            'method' => 'put',
            'route' => '/(:any)',
            'uses' => ':name@item',
        ),
        array(
            'method' => 'delete',
            'route' => '/(:any)',
            'uses' => ':name@item',
        ),
    );

    protected $valid_methods = array('get', 'post', 'put', 'delete');

    /**
     * Create new routes
     *
     * @param   string  $name The name of the route pluralized
     * @param   array   $template Optionally pass in a template as well
     * @throws \Exception invalid method
     */
    public function __construct($name, $template = array())
	{
        $this->parent = '';
        $this->parent_singular = '';
        $prefix = '';

        // Use the custom template if its been provided
        if ( ! empty($template)) {
            $this->template = $template;
        }

        // We allow the name to be set in a sub-folder
        // so that restful routes can be created for x-to-many relationships
        $pieces = explode('.', $name);
        if ( ! empty($pieces[1])) {
            $this->parent = $pieces[0];
            $this->parent_singular = Str::singular($this->parent);
            $prefix =  $this->parent . '/(:any?)/';
        }

        // If there isn't a second piece, then just use the name
        $this->name = (empty($pieces[1])) ? $name : $pieces[1];
        $this->singular = Str::singular($this->name);

        // Create each route in the template
        foreach($this->template as $route) {
            $method = strtolower(($route['method']));
            if ( ! in_array($method, $this->valid_methods)) {
                throw new \Exception('Invalid method specified:' . $method);
            }
            $options = $this->build_options($route);

            Route::$method($prefix.$this->name.$route['route'], $options);
        }
	}

	/**
	 * Static method to create a new set of routes
	 *
	 * @param  string $name The plural route name
	 * @param  array  $template Optional route template
	 * @return void
	 */
	public static function make($name, $template = array())
	{
	    new Routely($name, $template);
	}

    /**
     * Build the options for each route
     *
     * @param string $route
     * @return array
     */
    protected function build_options($route)
    {
        $options = array();
        if ( ! empty($route['as'])) {
            $prefix = (empty($this->parent_singular)) ? '' : $this->parent_singular . '_';
            $options['as'] = $prefix . $this->replace_tags($route['as']);
        }
        if ( ! empty($route['uses'])) {
            $prefix = (empty($this->parent)) ? '' : $this->parent . '.';
            $options['uses'] = $prefix . $this->replace_tags($route['uses']);
        }
        return $options;
    }

    /**
     * Replace tags in the route options
     *
     * @param string $string
     * @return string
     */
    protected function replace_tags($string)
    {
        $string = str_replace(':singular', $this->singular, $string);
        return str_replace(':name', $this->name, $string);
    }
}