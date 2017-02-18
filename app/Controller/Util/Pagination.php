<?php

namespace BrasseursApplis\Arrows\App\Controller\Util;

use Doctrine\ORM\Tools\Pagination\Paginator as OrmPaginator;

class Pagination
{
    /** @var OrmPaginator */
    private $elements;

    /** @var int */
    private $page;

    /** @var int */
    private $elementsPerPage;

    /** @var string */
    private $sort;

    /**
     * Pagination constructor.
     *
     * @param Paginator $elements
     * @param int       $page
     * @param int       $elementsPerPage
     * @param string    $sort
     */
    public function __construct(
        $elements,
        $page,
        $elementsPerPage,
        $sort
    ) {
        $this->elements = $elements;
        $this->page = $page;
        $this->elementsPerPage = $elementsPerPage;
        $this->sort = $sort;
    }

    /**
     * @return array
     */
    public function getElements()
    {
        return $this->elements->getIterator()->getArrayCopy();
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getElementsPerPage()
    {
        return $this->elementsPerPage;
    }

    /**
     * @return string
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @return int
     */
    public function getTotalElements()
    {
        return $this->elements->count();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'elements' => $this->getElements(),
            'pagination' => [
                'page' => $this->getPage(),
                'nbElements' => $this->getElementsPerPage(),
                'sort' => $this->getSort(),
                'totalElements' => $this->getTotalElements(),
                'totalPages' => ceil($this->getTotalElements() / $this->getElementsPerPage())
            ]
        ];
    }
}
