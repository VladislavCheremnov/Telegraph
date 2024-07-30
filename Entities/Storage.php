<?php
namespace App\Entities;

abstract class Storage 
{
    abstract function create(TelegraphText $object): string;
    abstract function read(string $slug): TelegraphText;
    abstract function update(string $slug, TelegraphText $object): void;
    abstract function delete(string $slug): void;
    abstract function list(): array;
}
