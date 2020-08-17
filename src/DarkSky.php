<?php

namespace Naughtonium\LaravelDarkSky;

use GuzzleHttp\Client;

class DarkSky
{
    protected $apiKey;
    protected $endpoint = 'https://api.darksky.net/forecast/';
    protected $lat;
    protected $lon;
    protected $timestamp = null;
    protected $params = [];
    protected $excludeables = ['currently', 'minutely', 'hourly', 'daily', 'alerts', 'flags'];

    public function __construct()
    {
        $this->apiKey = config('darksky.apikey');
        $this->endpoint = $this->endpoint.$this->apiKey;
    }

    /**
     * Sets the latitude and longitude. Must be set.
     */
    public function location($lat, $lon): self
    {
        $this->lat = $lat;
        $this->lon = $lon;

        return $this;
    }

    /**
     * Builds the endpoint url and sends the get request
     */
    public function get()
    {
        $url = $this->endpoint.'/'.$this->lat.','.$this->lon;

        if ($this->timestamp) {
            $url .= ','.$this->timestamp;
        }

        $client = new Client();

        return json_decode($client->get($url, [
            'query' => $this->params,
        ])->getBody());
    }

    /**
     * Sets the exclude query parameter by taking array of exclusions
     */
    public function excludes(array $blocks): self
    {
        $this->params['exclude'] = implode(',', $blocks);

        return $this;
    }

    /**
     * Sets the exclude query parameter by taking an array of inclusions
     */
    public function includes(array $blocks): self
    {
        $this->params['exclude'] = implode(',', array_diff($this->excludeables, $blocks));

        return $this;
    }

    /**
     * Specifies a unix timestamp for non-current forecasts
     * See: https://darksky.net/dev/docs/time-machine
     */
    public function atTime($timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Extends the number of hours returned in hourly from 48 to 168
     */
    public function extend(): self
    {
        $this->params['extend'] = 'hourly';

        return $this;
    }

    /**
     * Sets the return language
     */
    public function language($lang): self
    {
        $this->params['lang'] = $lang;

        return $this;
    }

    /**
     * Sets the return units
     */
    public function units($unit): self
    {
        $this->params['units'] = $unit;

        return $this;
    }

    ///////////////////////////////////////////////////////////////////
    //////////////////////////// HELPERS //////////////////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * Filters out metadata to get only currently
     */
    public function currently(): self
    {
        return $this->includes(['currently'])->get()->currently;
    }

    /**
     * Filters out metadata to get only minutely
     */
    public function minutely(): self
    {
        return $this->includes(['minutely'])->get()->minutely->data;
    }

    /**
     * Filters out metadata to get only hourly
     */
    public function hourly(): self
    {
        return $this->includes(['hourly'])->get()->hourly->data;
    }

    /**
     * Filters out metadata to get only daily
     */
    public function daily(): self
    {
        return $this->includes(['daily'])->get()->daily->data;
    }

    /**
     * Filters out metadata to get only alerts
     */
    public function alerts(): self
    {
        return $this->includes(['alerts'])->get();
    }

    /**
     * Filters out metadata to get only flags
     */
    public function flags(): self
    {
        return $this->includes(['flags'])->get()->flags;
    }
}
