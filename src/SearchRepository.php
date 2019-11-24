<?php


namespace Waxwink\SearchRepository;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

abstract class SearchRepository
{
    /**
     * The attributes which are filterable
     *
     * @var array
     */
    protected $filterable_attributes = [];

    /**
     * The attributes which are searchable
     *
     * @var array
     */
    protected $searchable_attributes = [];

    /**
     * @var Builder
     */
    protected $query;

    /**
     * Filtering through records with given filters
     *
     * @param array $filters
     * @return Builder
     */
    public function filter(array $filters)
    {
        foreach ($filters as $key => $value){
            //check if the key is valid
            if (! in_array($key, $this->filterable_attributes)){continue;}

            // if the value is string or number
            if (is_string($value) || is_numeric($value)){

                if ($date =Carbon::make($value)){
                    $start = $date->setHour(0)->setMinute(0)->setSecond(0)->toDateTimeString();
                    $end = $date->setHour(23)->setMinute(59)->setSecond(59)->toDateTimeString();
                    $this->query = $this->query->where($key, ">=", $start)->where($key, "<", $end);
                    continue;
                }

                $this->query = $this->query->where($key, $value);
                continue;
            }

            if (array_key_exists(1,$value)){
                //if the value is array and it has operators in its own values
                if (in_array($value[1], ['>', '=', '<', '!=', 'like'])){
                    $this->query = $this->query->where($key,$value[1] , $value[0]);
                    continue;
                }
            }

            // check if the value has the key "from" or "to"
            if (array_key_exists("from", $value)){
                $this->query = $this->query->where($key,">=" , $value["from"]);
            }

            if (array_key_exists("to", $value)){
                $this->query = $this->query->where($key,"<" , $value["to"]);
                continue;
            }

            if (array_values($value) == $value){
                //if the value is array and has not operator then it has multiple values in it
                $this->query = $this->query->whereIn($key,$value);
            }
        }

        return $this->query;

    }

    /**
     * Searching through filtered data
     *
     * @param $value
     * @return Builder
     */
    public function search($value)
    {
        // replace space with %
        $value = str_replace(' ', '%', $value);

        // prepare searchable attributes for put on query
        $searchable_attributes = implode(',', $this->searchable_attributes);

        // concat database columns and search through them
        return $this->query
            ->where(DB::raw("CONCAT($searchable_attributes)"), 'LIKE', "%" . $value . '%');
    }

    /**
     * Returns the query object
     *
     * @return Builder
     */
    public function getQuery(): Builder
    {
        return $this->query;
    }

}
