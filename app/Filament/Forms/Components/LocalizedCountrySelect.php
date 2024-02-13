<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\App;
use League\ISO3166\ISO3166;
use Locale;

class LocalizedCountrySelect extends Select
{
    protected function setUp(): void
    {
        parent::setUp();

        $iso3166 = new ISO3166();

        foreach ($iso3166 as $data) {
            $this->options[$data['alpha2']] = Locale::getDisplayRegion("-{$data['alpha2']}", App::currentLocale());
        }
    }
}
