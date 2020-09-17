<?php

namespace app\models;

interface VideoSearchQueryInterface {
    function getOrderColumn(): string;
    function getIsAsc(): bool;
    function getOffsetId(): int;
    function getPageNumber(): int;
    function getPageSize(): int;
}

