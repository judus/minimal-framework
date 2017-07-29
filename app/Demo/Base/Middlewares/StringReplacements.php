<?php namespace Acme\Demo\Base\Middlewares;

use Maduser\Minimal\Middlewares\AbstractMiddleware;

/**
 * Class Cache
 *
 * @package Maduser\Minimal\Middlewares
 */
class StringReplacements extends AbstractMiddleware
{
    /**
     * @return mixed
     */
    public function after()
    {
       $this->setPayload($this->replaceTags($this->getPayload()));
    }

    /**
     * @param $content
     *
     * @return mixed
     */
    public function replaceTags($content)
    {
        $tags = $this->findTags($content);

        foreach ($tags as $tag) {
            $content = $this->replace($tag, $content);
        }

        return $content;
    }

    /**
     * @param $content
     *
     * @return array
     */
    public function findTags($content)
    {
        preg_match_all('/(?:\{{(.*?)(?:(:([a-zA-Z0-9\s\W]+))?)\}})/',
            $content, $matches);

        $tags = [];

        for ($i = 0; $i < count($matches[0]); $i++) {
            $m = [];
            $m[] = $matches[0][$i];
            $m[] = $matches[1][$i];
            if (isset($matches[2])) {
                if (!empty($matches[2][$i])) {
                    $m[] = trim($matches[2][$i], ':');
                } else {
                    $m[] = null;
                }
            }
            $tags[] = $m;
        }

        foreach ($tags as &$tag) {
            $i = 0;
            array_walk($tag, function (&$value, $i) {
                if ($i == 2 && preg_match('/:/', ($value))) {
                    $value = explode(':', $value) ;
                } else if ($i == 2) {
                    empty($value) || $value = [$value];
                }
                $i++;
            });
        }

        return $tags;
    }

    /**
     * @param $tag
     * @param $content
     *
     * @return mixed
     */
    public function replace($tag, $content)
    {
        return $this->{'tag_' . $tag[1]}($tag, $content);
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this, 'defaultReplacement'], $arguments);
    }

    /**
     * @param $tag
     * @param $content
     *
     * @return mixed
     */
    public function defaultReplacement($tag, $content)
    {
        !empty($tag[2]) || $tag[2] = 'null';
        is_string($tag[2]) || $tag[2] = '[' . implode(', ', $tag[2]) . ']';
        $result = '<strong>(' . $tag[1] . ' = ' . $tag[2] . ')</strong>';
        $content = str_replace('<p>' . $tag[0] . '</p>', $tag[0], $content);
        $content = str_replace($tag[0], $result, $content);
        return $content;
    }

}