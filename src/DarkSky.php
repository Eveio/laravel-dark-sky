<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 6/13/17
 * Time: 3:26 PM
 */

namespace Naughtonium\LaravelDarkSky;


class DarkSky
{
    protected $apiKey;
    protected $endpoint = 'https://api.darksky.net/forecast/';
    protected $lat;
    protected $lon;
    protected $timestamp = null;
    protected $params = [];
    protected $excludeables = ['currently', 'minutely', 'hourly', 'daily', 'alerts', 'flags'];

    /**
     * DarkSky constructor.
     */
    public function __construct()
    {
        $this->apiKey = config('darksky.apikey');
        $this->endpoint = $this->endpoint . $this->apiKey;
    }

    /**
     * Sets the latitude and longitude. Must be set
     *
     * @param $lat
     * @param $lon
     * @return $this
     */
    public function location($lat, $lon)
    {
        $this->lat = $lat;
        $this->lon = $lon;
        return $this;
    }

    /**
     * Builds the endpoint url and sends the get request
     *
     * @return mixed
     */
    public function get()
    {
        $url = $this->endpoint  . '/' . $this->lat . ',' . $this->lon;

        if ($this->timestamp) {
            $url .= ',' . $this->timestamp;
        }

        $client = new \GuzzleHttp\Client();

        return json_decode($client->get($url, [
            'query' => $this->params,
        ])->getBody());
    }

    /**
     * Sets the exclude query parameter by taking array of exclusions
     *
     * @param $blocks
     * @return $this
     */
    public function excludes($blocks)
    {
        $this->params['exclude'] = implode(',', $blocks);
        return $this;
    }

    /**
     * Sets the exclude query parameter by taking an arry of inclusions
     *
     * @param $blocks
     * @return $this
     */
    public function includes($blocks)
    {
        $this->params['exclude'] = implode(',', array_diff($this->excludeables, $blocks));
        return $this;
    }

    /**
     * Specifies a unix timestamp for non-current forecasts
     * See: https://darksky.net/dev/docs/time-machine
     *
     * @param $timestamp
     * @return $this
     */
    public function atTime($timestamp)
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * Extends the number of hours returned in hourly from 48 to 168
     *
     * @return $this
     */
    public function extend()
    {
        $this->params['extend'] = 'hourly';
        return $this;
    }

    /**
     * Sets the return language
     *
     * @param $lang
     * @return $this
     */
    public function language($lang)
    {
        $this->params['lang'] = $lang;
        return $this;
    }
}