<?php namespace Acme\Controllers;

/**
 * Class YourController
 *
 * @package Acme\Pages\Controllers
 */
class YourController
{
    public function yourMethod($firstname, $lastname)
    {
        return 'Welcome ' . ucfirst($firstname) . ' ' . ucfirst($lastname);
    }

    public function timeConsumingAction()
    {
        $countTo = 1000000000;

        $start = time();
        for ($i = 0; $i < $countTo; $i++) {
            $i;
        }
        $end = time();

        $period = $end - $start;

        $html = '<p>I have counted to ' . $countTo . '. It took '
            . $period.' seconds.<br>If the response reached you faster than '
            . 'that, you received cached contents</p>'
            . '<p>Content generated at '.date('Y-m-d h:i:sa').'</p>'
            .'<p>Cache is valid for 10 seconds. Press ctrl+R or cmd+R to reload.</p>';

        return $html;
    }
}