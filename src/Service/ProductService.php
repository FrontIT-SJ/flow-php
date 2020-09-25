<?php

namespace Flow\Service;

class ProductService extends AbstractService
{
    use \Flow\ApiOperations\All;
    use \Flow\ApiOperations\Retrieve;
    use \Flow\ApiOperations\Update;
    use \Flow\ApiOperations\Create;

    protected function getClassUrl(): string
    {
        return "products";
    }
}