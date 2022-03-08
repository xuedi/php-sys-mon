<?php

namespace Xuedi\PhpSysMon;

class Sensor
{
    private string $ident;
    private string $provider;

    public static function fromParameters(string $provider, string $ident): self
    {
        return new self($provider, $ident);
    }

    private function __construct(string $provider, string $ident)
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
