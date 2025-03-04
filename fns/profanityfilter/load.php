<?php

namespace Snipe\BanBuilder;

class CensorWords
{
    public array $badwords;
    private ?array $censorChecks = null;
    private array $whiteList = [];
    private string $whiteListPlaceHolder = ' {whiteList[i]} ';
    private string $replacer = '*';

    public function __construct() {
        $this->badwords = [];
        $this->setDictionary('en-us');
    }

    public function setDictionary(string|array $dictionary): void
    {
        $this->badwords = $this->readBadWords($dictionary);
    }

    public function addDictionary(string|array $dictionary): void
    {
        $this->badwords = array_merge($this->badwords, $this->readBadWords($dictionary));
    }

    public function addFromArray(array $words): void
    {
        $badwords = array_merge($this->badwords, $words);
        $this->badwords = array_keys(array_count_values($badwords));
    }

    private function readBadWords(string|array $dictionary): array
    {
        $badwords = [];
        $baseDictPath = __DIR__ . DIRECTORY_SEPARATOR . 'dict' . DIRECTORY_SEPARATOR;

        if (is_array($dictionary)) {
            foreach ($dictionary as $dictionary_file) {
                $badwords = array_merge($badwords, $this->readBadWords($dictionary_file));
            }
        }

        if (is_string($dictionary)) {
            $filePath = $baseDictPath . $dictionary . '.php';
            if (file_exists($filePath)) {
                include $filePath;
            } elseif (file_exists($dictionary)) {
                include $dictionary;
            } else {
                throw new \RuntimeException('Dictionary file not found: ' . $dictionary);
            }
        }

        return array_keys(array_count_values($badwords));
    }

    public function addWhiteList(array $list): void
    {
        foreach ($list as $value) {
            if (is_string($value) && !empty($value)) {
                $this->whiteList[]['word'] = $value;
            }
        }
    }

    private function replaceWhiteListed(string $string, bool $reverse = false): string
    {
        foreach ($this->whiteList as $key => $list) {
            if ($reverse && !empty($this->whiteList[$key]['placeHolder'])) {
                $placeHolder = $this->whiteList[$key]['placeHolder'];
                $string = str_replace($placeHolder, $list['word'], $string);
            } else {
                $placeHolder = str_replace('[i]', $key, $this->whiteListPlaceHolder);
                $this->whiteList[$key]['placeHolder'] = $placeHolder;
                $string = str_replace($list['word'], $placeHolder, $string);
            }
        }

        return $string;
    }

    public function setReplaceChar(string $replacer): void
    {
        $this->replacer = $replacer;
    }

    public function randCensor(string $chars, int $len): string
    {
        return str_shuffle(
            str_repeat($chars, (int)($len / strlen($chars))) .
            substr($chars, 0, $len % strlen($chars))
        );
    }

    private function generateCensorChecks(bool $fullWords = false): void
    {
        $badwords = $this->badwords;
        $leet_replace = [
            'a' => '(a|a\.|a\-|4|@|Á|á|À|Â|à|Â|â|Ä|ä|Ã|ã|Å|å|α|Δ|Λ|λ)',
            'b' => '(b|b\.|b\-|8|\|3|ß|Β|β)',
            'c' => '(c|c\.|c\-|Ç|ç|¢|€|<|\(|{|©)',
            'd' => '(d|d\.|d\-|&part;|\|\)|Þ|þ|Ð|ð)',
            'e' => '(e|e\.|e\-|3|€|È|è|É|é|Ê|ê|∑)',
            'f' => '(f|f\.|f\-|ƒ)',
            'g' => '(g|g\.|g\-|6|9)',
            'h' => '(h|h\.|h\-|Η)',
            'i' => '(i|i\.|i\-|!|\||\]\[|]|1|∫|Ì|Í|Î|Ï|ì|í|î|ï)',
            'j' => '(j|j\.|j\-)',
            'k' => '(k|k\.|k\-|Κ|κ)',
            'l' => '(l|1\.|l\-|!|\||\]\[|]|£|∫|Ì|Í|Î|Ï)',
            'm' => '(m|m\.|m\-)',
            'n' => '(n|n\.|n\-|η|Ν|Π)',
            'o' => '(o|o\.|o\-|0|Ο|ο|Φ|¤|°|ø)',
            'p' => '(p|p\.|p\-|ρ|Ρ|¶|þ)',
            'q' => '(q|q\.|q\-)',
            'r' => '(r|r\.|r\-|®)',
            's' => '(s|s\.|s\-|5|\$|§)',
            't' => '(t|t\.|t\-|Τ|τ|7)',
            'u' => '(u|u\.|u\-|υ|µ)',
            'v' => '(v|v\.|v\-|υ|ν)',
            'w' => '(w|w\.|w\-|ω|ψ|Ψ)',
            'x' => '(x|x\.|x\-|Χ|χ)',
            'y' => '(y|y\.|y\-|¥|γ|ÿ|ý|Ÿ|Ý)',
            'z' => '(z|z\.|z\-|Ζ)',
        ];


        $censorChecks = [];
        foreach ($badwords as $x => $badword) {
            $censorChecks[$x] = $fullWords
            ? '/\b' . str_ireplace(array_keys($leet_replace), array_values($leet_replace), $badword) . '\b/i'
            : '/' . str_ireplace(array_keys($leet_replace), array_values($leet_replace), $badword) . '/i';
        }

        $this->censorChecks = $censorChecks;
    }

    public function censorString(string $string, bool $fullWords = false): array
    {
        if (!$this->censorChecks) {
            $this->generateCensorChecks($fullWords);
        }

        $anThis = &$this;
        $counter = 0;
        $match = [];
        $newstring = [];
        $newstring['orig'] = $string;
        $original = $this->replaceWhiteListed($newstring['orig']);
        $newstring['clean'] = preg_replace_callback(
            $this->censorChecks,
            function ($matches) use (&$anThis, &$counter, &$match) {
                $match[$counter++] = $matches[0];

                return (strlen($anThis->replacer) === 1)
                ? str_repeat($anThis->replacer, strlen($matches[0]))
                : $anThis->randCensor($anThis->replacer, strlen($matches[0]));
            },
            $original
        );
        $newstring['clean'] = $this->replaceWhiteListed($newstring['clean'], true);
        $newstring['matched'] = $match;

        return $newstring;
    }
}