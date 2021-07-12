<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GenresHasCategoriesRule implements Rule
{
    /**
     * @var array
     */
    private $categoriesId;

    /**
     * @var array
     */
    private $genresId;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(array $categoriesId)
    {
        $this->categoriesId = array_unique($categoriesId);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->genresId = array_unique($value);
        if (!count($this->genresId) || !count($this->categoriesId)) {
            return false;
        }

        $categoriesFound = [];

        foreach ($this->genresId as $genresId) {
            $rows = $this->getRows($genresId);
            if (!$rows->count()) {
                return false;
            }

            array_push($categoriesFound, ...$rows->pluck('category_id')->toArray());
        }

        if (count($categoriesFound) !== count($this->categoriesId)) {
            return false;
        }

        return true;
    }

    protected function getRows($genreId): Collection
    {
        return DB::table('category_genre')
            ->where('genre_id', $genreId)
            ->whereIn('category_id', $this->categoriesId)
            ->get();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'A genre ID must be related at least a category ID';
    }
}
