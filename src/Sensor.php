<?php

namespace Xuedi\PhpSysMon;

class Sensor
{
    private string $ident;
    private string $provider;

    public function __construct(string $provider, string $ident)
    {
        $this->provider = $provider;
        $this->ident = $ident;
    }

    public function getIdent(): string
    {
        return $this->ident;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }
}
