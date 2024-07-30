<?php
namespace App\Entities;
use App\Interfaces\IRender;

abstract class View implements IRender
{
    protected $templateName;
    protected $variables;

    public function __construct(string $templateName)
    {
        $this->templateName = $templateName;
    }

    public function addVariablesToTemplate(array $variables): void
    {
        $this->variables = $variables;
    }
}