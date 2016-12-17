<?php

if ( ! function_exists('show'))
{
    /**
     * @param null $data
     * @param null $heading
     * @param bool $getContents
     *
     * @return string
     */
	function show($data = NULL, $heading = NULL, $getContents = false) {
		!is_null($data) OR $data = 'Hi from '. debug_backtrace()[0]['file'] .
			' at line '. debug_backtrace()[0]['line'];

		$html = '<div class="debug_show">';
		$html.= $heading ? '<span>'.$heading : '';
		$html.= $heading ? '</span>' : '';
		$html.= '<pre>';
		$html.= htmlentities(print_r($data, true));
		$html.= '</pre>';
		$html.= '</div>';

		if ($getContents) {
			return $html;
        }
		echo $html;
	}
}