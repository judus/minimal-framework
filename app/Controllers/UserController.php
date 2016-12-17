<?php namespace Acme\Controllers;

/**
 * Class UserController
 *
 * @package Acme\Pages\Controllers
 */
/**
 * Class UserController
 *
 * @package Acme\Controllers
 */
class UserController
{
    /**
     * @return array
     */
    public function list()
    {
        return [
            [
                'firstname' => 'Jon',
                'lastname' => 'Doe',
                'username' => 'jondoe'
            ],
            [
                'firstname' => 'Jane',
                'lastname' => 'Doe',
                'username' => 'janedoe'
            ],
        ];
    }

    /**
     * @return string
     */
    public function create()
    {
        return 'Imagine a user form';
    }
}