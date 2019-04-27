<?php

namespace App\ValueObjects;

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
        if ($url != '' && ! mb_strpos($url, ['https', 'http', 'ftp']) ) {
            throw new \App\Exceptions\NotAnUrlException("Provided link is not an url");
        }

        $this->url = $url;
    }
}
