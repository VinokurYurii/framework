<?php

namespace Yurii\Response;
/**
 * Class JsonResponse
 * @package Yurii\Response
 */
class JsonResponse extends Response{
    function __construct($jsonContent) {
        $content = self::parse($jsonContent);
        parent::__construct($content, 'application/json');
    }

    /**
     * @param $content
     * @return string
     *
     * parse json data
     */
    public static function parse($content) {
        return json_encode($content);
    }
}