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

    protected $template = array(
        array(
            'method' => 'get',
            'route' => '/(:any)/edit',
            'as' => ':singular_edit',
            'uses' => ':name@edit',
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
            'uses' => ':name@view',
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
            'uses' => ':name@create',
        ),
        array(
            'method' => 'put',
            'route' => '/(:any)',
            'uses' => ':name@update',
        ),
        array(
            'method' => 'delete',
            'route' => '/(:any)',
            'uses' => ':name@destroy',
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
        $this->name = $name;
        $this->singular = Str::singular($name);

        if ( ! empty($template)) {
            $this->template = $template;
        }

        foreach($this->template as $route) {
            $method = strtolower(($route['method']));
            if ( ! in_array($method, $this->valid_methods)) {
                throw new \Exception('Invalid method specified:' . $method);
            }
            $options = $this->build_options($route);
            Route::$method($name.$route['route'], $options);
            print_r("Route::".$method."('".$name.$route["route"]."',". print_r($options).")");
        }
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
            $options['as'] = $this->replace_tags($route['as']);
        }
        if ( ! empty($route['uses'])) {
            $options['uses'] = $this->replace_tags($route['uses']);
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