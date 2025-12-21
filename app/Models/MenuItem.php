<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'parent_id',
        'page_id',
        'title',
        'url',
        'route',
        'order',
        'target',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Get the actual URL for the menu item.
     */
    public function getLinkAttribute(): string
    {
        if ($this->page_id) {
            return route('pages.show', $this->page->slug);
        }

        if ($this->route) {
            try {
                return route($this->route);
            } catch (\Exception $e) {
                return '#';
            }
        }

        return $this->url ?? '#';
    }
}