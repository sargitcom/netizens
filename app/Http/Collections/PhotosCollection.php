<?php

namespace App\Http\Collections;

use App\Resources\Entity\Domain;

class DomainCollection extends Collection
{
    public function current(): Domain
    {
        return $this->array[$this->position];
    }

    public function append(Domain $domain)
    {
        $this->array[$this->position++] = $domain;
    }
}
