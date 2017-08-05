<?php namespace App\Demo\Base\Controllers;

/**
 * Class YourController
 *
 * @package App\Demo\Base\Controllers
 */
class YourController
{
    /**
     * @param $firstname
     * @param $lastname
     *
     * @return string
     */
    public function yourMethod($firstname, $lastname)
    {
        return 'Welcome ' . ucfirst($firstname) . ' ' . ucfirst($lastname);
    }

    /**
     * @return string
     */
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

    /**
     * @return string
     */
    public function loremIpsum()
    {
        return 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, ' .
            'sed diam nonumy {{MODULE_A}} eirmod tempor invidunt ut labore et dolore ' .
            'magna aliquyam erat, {{MODULE_D:qwe}}sed diam voluptua. At vero eos et accusam ' .
            'et justo duo dolores et ea rebum. Stet clita kasd gubergren, ' .
            'no sea takimata {{MODULE_B:123}} sanctus est Lorem ipsum dolor sit amet. Lorem ' .
            'ipsum dolor{{MODULE_E789:456}} sit amet, consetetur sadipscing elitr, sed diam ' .
            'nonumy eirmod tempor invidunt ut labore et dolore magna ' .
            'aliquyam erat, sed diam {{MODULE_C:789:456:xcv}} voluptua. At vero eos et accusam et ' .
            'justo duo dolores et ea rebum. Stet clita kasd gubergren, no ' .
            'sea takimata sanctus est Lorem ipsum dolor sit amet.';
    }
}