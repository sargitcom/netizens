<?php

namespace App\Http\ValueObjects;

class Url
{
    /**
     * @var string
     */
    protected $url;

    /**
     * UrlVO constructor.
     * @param string $url
     * @throws \App\Exceptions\NotAnUrlException
     */
    public function __construct(string $url)
    {
        if ($url != '' && ! $this->isValidUrl($url)) {
            throw new \App\Exceptions\NotAnUrlException("Provided link is not an url");
        }

        $this->url = $url;
    }

    protected function isValidUrl($url)
    {
        if(mb_strpos($url, 'https') === 0) {
            return true;
        }

        if(mb_strpos($url, 'http') === 0) {
            return true;
        }

        if(mb_strpos($url, 'ftp') === 0) {
            return true;
        }

        return false;
    }

    public function getUrl() : string
    {
        return $this->url;
    }
}
