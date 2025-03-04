<?php
class SwearFilterNode {
    public $children;
    public $isEndOfWord;

    public function __construct() {
        $this->children = [];
        $this->isEndOfWord = false;
    }
}

class SwearFilter {
    private $root;

    public function __construct() {
        $this->root = new SwearFilterNode();
    }

    public function insert($word) {
        $node = $this->root;
        $length = mb_strlen($word, 'UTF-8');

        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($word, $i, 1, 'UTF-8');
            if (!isset($node->children[$char])) {
                $node->children[$char] = new SwearFilterNode();
            }
            $node = $node->children[$char];
        }
        $node->isEndOfWord = true;
    }

    public function insertBadWordsFromFile($filePath) {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new Exception("File not found or not readable:$filePath");
        }

        $badWords = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($badWords === false) {
            throw new Exception("Error reading file:$filePath");
        }

        foreach ($badWords as $word) {
            $this->insert(mb_strtolower($word, 'UTF-8'));
        }
    }

    public function strict_filter($text, $replacement = '****') {
        $textLowercase = mb_strtolower($text, 'UTF-8');
        $node = $this->root;
        $censoredText = $text;
        $offset = 0;

        $length = mb_strlen($textLowercase, 'UTF-8');
        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($textLowercase, $i, 1, 'UTF-8');
            if (isset($node->children[$char])) {
                $node = $node->children[$char];
                $j = $i + 1;
                while ($j < $length && isset($node->children[mb_substr($textLowercase, $j, 1, 'UTF-8')])) {
                    $node = $node->children[mb_substr($textLowercase, $j, 1, 'UTF-8')];
                    $j++;
                }
                if ($node->isEndOfWord) {
                    $replacementStr = str_repeat('*', $j - $i);
                    $censoredText = mb_substr($censoredText, 0, $i + $offset) . $replacementStr . mb_substr($censoredText, $j + $offset, null, 'UTF-8');
                    $offset += mb_strlen($replacementStr, 'UTF-8') - ($j - $i);
                    $i = $j - 1;
                }
                $node = $this->root;
            }
        }

        return $censoredText;
    }

    public function filter($text, $replacement = '****') {
        $textLowercase = mb_strtolower($text, 'UTF-8');
        $node = $this->root;
        $censoredText = $text;
        $offset = 0;

        $length = mb_strlen($textLowercase, 'UTF-8');
        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($textLowercase, $i, 1, 'UTF-8');
            if (isset($node->children[$char])) {
                $node = $node->children[$char];
                $j = $i + 1;
                while ($j < $length && isset($node->children[mb_substr($textLowercase, $j, 1, 'UTF-8')])) {
                    $node = $node->children[mb_substr($textLowercase, $j, 1, 'UTF-8')];
                    $j++;
                }
                if ($node->isEndOfWord) {
                    $prevChar = ($i - 1 >= 0) ? mb_substr($textLowercase, $i - 1, 1, 'UTF-8') : '';
                    $nextChar = ($j < $length) ? mb_substr($textLowercase, $j, 1, 'UTF-8') : '';
                    $isWordBoundary = !ctype_alpha($prevChar) && !ctype_alpha($nextChar);
                    if ($isWordBoundary) {
                        $replacementStr = str_repeat('*', $j - $i);
                        $censoredText = mb_substr($censoredText, 0, $i + $offset) . $replacementStr . mb_substr($censoredText, $j + $offset, null, 'UTF-8');
                        $offset += mb_strlen($replacementStr, 'UTF-8') - ($j - $i);
                        $i = $j - 1;
                    }
                }
                $node = $this->root;
            }
        }

        return $censoredText;
    }
}