<?php

namespace app\models;

use yii\web\Request;

/**
 * Model SearchQuery
 *  The model for preparing query parameters by request.
 * @package app\models
 */
class SearchQuery implements VideoSearchQueryInterface
{
    /**
     * @var string column to order
     */
    private $orderColumn = Video::ORDER_COLUMN_VIEWS;
    /**
     * @var bool direction: asc or desc
     */
    private $isAsc = false;
    /**
     * @var int reference id
     */
    private $offsetId = 0;
    /**
     * @var int page number from request
     */
    private $pageNumber = 0;
    /**
     * @var int maximum amount of items to return
     */
    private $pageSize = 24;

    private $errors = [];

    private function parsePositive($input): ?int{
        if (!is_int($input) && !ctype_digit($input)){
            return null;
        }
        $input = (int) $input;
        if (!($input > 0)){
            return null;
        }
        return $input;
    }

    private function readOrderColumn(string $input, bool $ignoreErrors = false): bool{
        switch ($input){
            case 'added_at':
                $this->orderColumn = Video::ORDER_COLUMN_ADDED_AT;
                break;
            case 'views':
            case '':
                $this->orderColumn = Video::ORDER_COLUMN_VIEWS;
                break;
            default:
                if (!$ignoreErrors) {
                    $this->errors[] = 'not valid mode';
                }
                return false;
        }
        return true;
    }

    private function readDirection(string $input, bool $ignoreErrors = false): bool{
        switch ($input){
            case 'asc':
                $this->isAsc = true;
                break;
            case 'desc':
            case '':
                $this->isAsc = false;
                break;
            default:
                if (!$ignoreErrors) {
                    $this->errors[] = 'not valid direction';
                }
                return false;
        }
        return true;
    }

    private function readPageSize($input, bool $ignoreErrors = false): bool{
        if (empty($input)) {
            return true;
        }
        $pageSize = $this->parsePositive($input);
        if ($pageSize === null){
            if (!$ignoreErrors) {
                $this->errors[] = 'not valid page size';
            }
            return false;
        }
        $this->pageSize = $pageSize;
        return true;
    }

    private function readOffsetId($input, bool $ignoreErrors = false): bool{
        if (empty($input)) {
            return true;
        }
        $offsetId = $this->parsePositive($input);
        if ($offsetId === null){
            if (!$ignoreErrors) {
                $this->errors[] = 'not valid offset id';
            }
            return false;
        }
        $this->offsetId = $offsetId;
        return true;
    }

    private function readPageNumber($input, bool $ignoreErrors = false): bool{
        if (empty($input)) {
            return true;
        }
        $page = $this->parsePositive($input);
        if ($page === null){
            $this->errors[] = 'not valid page';
            return false;
        }
        $this->pageNumber = $page;
        return true;
    }

    public function buildByApiRequest(Request $request): bool {
        if (!$this->readOrderColumn((string) $request->get('mode'))){
            return false;
        }
        if (!$this->readDirection((string) $request->get('direction'))){
            return false;
        }
        if (!$this->readPageSize($request->get('page_size'))){
            return false;
        }
        if (!$this->readOffsetId($request->get('offset_id'))){
            return false;
        }
        if (!$this->readPageNumber($request->get('page'))){
            return false;
        }
        return true;
    }

    public function buildByHttpRequest(Request $request): bool {
        $this->readOrderColumn((string) $request->get('mode'), true);
        $this->readDirection((string) $request->get('direction'), true);
        $this->readPageSize($request->get('page_size'), true);
        $this->readPageNumber($request->get('page'), true);
        return true;
    }

    public function getErrors(): array {
        return $this->errors;
    }

    /**
     * @return string
     */
    public function getOrderColumn(): string
    {
        return $this->orderColumn;
    }

    /**
     * @param string $orderColumn
     */
    public function setOrderColumn(string $orderColumn): void
    {
        $this->orderColumn = $orderColumn;
    }

    /**
     * @return bool
     */
    public function getIsAsc(): bool
    {
        return $this->isAsc;
    }

    /**
     * @param bool $isAsc
     */
    public function setIsAsc(bool $isAsc): void
    {
        $this->isAsc = $isAsc;
    }

    /**
     * @return int
     */
    public function getOffsetId(): int
    {
        return $this->offsetId;
    }

    /**
     * @param int $offsetId
     */
    public function setOffsetId(int $offsetId): void
    {
        $this->offsetId = $offsetId;
    }

    /**
     * @return int
     */
    public function getPageNumber(): int
    {
        return $this->pageNumber;
    }

    /**
     * @param int $pageNumber
     */
    public function setPageNumber(int $pageNumber): void
    {
        $this->pageNumber = $pageNumber;
    }

    /**
     * @return int
     */
    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    /**
     * @param int $pageSize
     */
    public function setPageSize(int $pageSize): void
    {
        $this->pageSize = $pageSize;
    }

}
