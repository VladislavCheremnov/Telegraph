<?php
namespace App\Core\Templates;

use App\Entities\TelegraphText;
use App\Entities\View;
use App\Interfaces\IRender;

class Swig extends View implements IRender
{
    public function render(TelegraphText $telegraphText): string
    {   
        $spl = file_get_contents(sprintf('templates/%s%s', $this->templateName, '.swig'));
        
        foreach($this->variables as $elem) {
            $spl = str_replace('{{ '.$elem.' }}', $telegraphText->$elem, $spl);
        }

        return $spl;
    }
}

