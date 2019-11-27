<?php


namespace Waxwink\SearchRepository\Concerns;


use Illuminate\Http\Request;
use Waxwink\SearchRepository\SearchRepository;

trait SearchTrait
{
    /**
     * @param $request
     * @param $searchRepository
     * @return mixed
     */
    public function filterAndSearch(Request $request, SearchRepository $searchRepository)
    {
        $results = $searchRepository->filter($request->all());

        if ($request->search)
            $results = $searchRepository->search($request->search);

        return $results;
    }

}
