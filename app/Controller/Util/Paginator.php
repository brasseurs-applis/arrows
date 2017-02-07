<?php

namespace BrasseursApplis\Arrows\App\Controller\Util;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Paginator
{
    /** @var Request */
    private $request;

    /**
     * Paginator constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param \Closure $getElements
     *
     * @return Pagination
     *
     * @throws NotFoundHttpException
     */
    public function getPaginatedArray(\Closure $getElements)
    {
        $page = $this->request->get('page') ? : 1;
        $elementsPerPage = $this->request->get('elements') ? : 20;
        $sortParamString = $this->request->get('sort') ? : '';
        $sort = self::processSorting($sortParamString);

        $paginatedElements = $getElements($sort, $page, $elementsPerPage);
        $pagination = new Pagination($paginatedElements, $page, $elementsPerPage, $sortParamString);

        return $pagination;
    }

    /**
     * @param string $sortParamString
     *
     * @return array
     */
    private static function processSorting($sortParamString)
    {
        $sort = array_reduce(explode(',', $sortParamString), function ($sortArray, $sortString) {
            if ($sortString === '') {
                return $sortArray;
            }

            $sortParameters = explode(':', $sortString);
            $orientation = isset($sortParameters[1]) ? strtoupper($sortParameters[1]) : 'ASC';
            if (!in_array($orientation, ['ASC', 'DESC'], true)) {
                $orientation = 'ASC';
            }
            $sortArray[$sortParameters[0]] = $orientation;

            return $sortArray;
        }, []);

        return $sort;
    }
}
