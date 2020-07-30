<?php

declare(strict_types=1);

namespace xisrapilx\mysqlo\utils;

use Exception;

class Utils{

    /**
     * Extracts one-line tags from the doc-comment
     *
     * @param string $docComment
     *
     * @return string[] an array of tagName => tag value. If the tag has no value, an empty string is used as the value.
     * @throws Exception
     *
     * @author PocketMine Team
     * @link http://www.pocketmine.net/
     */
    public static function parseDocComment(string $docComment) : array{
        $rawDocComment = substr($docComment, 3, -2); //remove the opening and closing markers
        if($rawDocComment === false){ //usually empty doc comment, but this is safer and statically analysable
            return [];
        }
        preg_match_all('/(*ANYCRLF)^[\t ]*(?:\* )?@([a-zA-Z]+)(?:[\t ]+(.+?))?[\t ]*$/m', $rawDocComment, $matches);

        $result = array_combine($matches[1], $matches[2]);
        if(!$result)
            throw new Exception();

        return $result;
    }
}