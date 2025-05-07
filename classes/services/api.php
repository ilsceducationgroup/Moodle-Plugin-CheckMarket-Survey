<?php

namespace mod_ilsccheckmarket\services;

use stdClass;
use tool_monitor\output\managesubs\subs;

defined('MOODLE_INTERNAL') || die();

class api
{
    public $masterkey;
    public $key;
    public $base_url;
    public $routes = [];
    public $top = 50;
    public $skip = 0;
    public $select = '';
    public $filter = '';
    public $orderby = '';
    public string $expand = '';

    public function __construct($masterkey, $key)
    {
        $this->masterkey = $masterkey;
        $this->key = $key;
    }

    public function get_data(): object
    {
        $response = self::execute();
        $json_response = json_decode($response);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON response');
        }
        return $json_response;   
    }

    public function execute(): string
    {
        $base_url = $this->get_api_full_url();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $base_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'X-Master-Key: ' . $this->masterkey,
                'X-Key: ' . $this->key
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function get_api_full_url(): string
    {
        $options = [
            'top',
            'skip',
            'select',
            'filter',
            'orderby',
            'expand',
        ];

        $base_url = rtrim($this->base_url, '/') . '/';
        $base_url .= implode('/', $this->routes);

        $query_params = [];
        foreach ($options as $option) {
            if (isset($this->$option) && !empty($this->$option)) {
                $query_params[$option] = $this->$option;
            }
        }

        if (!empty($query_params)) {
            $base_url .= '?' . http_build_query($query_params, '', '&', PHP_QUERY_RFC3986);
        }

        return $base_url;
    }

    /**
    * https://api-ca.agileresearch.medallia.com/Docs/Query 
    */
    public function add_filter(string $field, string $operator, $value, $combine_operator = 'and'): string
    {
        if (!in_array($operator, ['eq', 'ne', 'gt', 'ge', 'lt', 'le'])) {
            throw new \Exception('Invalid operator');
        }

        $operator = " $operator ";

        if (!in_array($combine_operator, ['and', 'or'])) {
            throw new \Exception('Invalid combine operator');
        }
        
        if (!empty($this->filter)) {
            $this->filter .= " $combine_operator ";
        }
        $this->filter .= "$field$operator$value";
        return $this->filter;
    }

    public function add_route(string $route): void
    {
        $this->routes[] = $route;
    }

    public function reset_routes(): void
    {
        $this->routes = [];
    }
}
