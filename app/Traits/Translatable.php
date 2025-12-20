<?php

namespace App\Traits;

use App\Models\Translation;
use Illuminate\Support\Facades\App;

trait Translatable
{
    public function translations()
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    public function getTranslation(string $field, ?string $locale = null)
    {
        $locale = $locale ?? App::getLocale();

        $translation = $this->translations()
            ->where('language_code', $locale)
            ->where('field_name', $field)
            ->first();

        return $translation ? $translation->field_value : $this->getAttribute($field);
    }

    // Helper to auto-translate properties
    public function getAttribute($key)
    {
        if (in_array($key, $this->translatable ?? [])) {
            return $this->getTranslation($key);
        }

        return parent::getAttribute($key);
    }
}
