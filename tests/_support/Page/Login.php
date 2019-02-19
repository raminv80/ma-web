<?php
namespace Page;

class Login
{
    // include url of current page
    public static $URL = '/login';

    public static $loginForm = '#login-form';
    public static $usernameField = '#username';
    public static $passwordField = '#password1';
    public static $loginButton = '#login-form button[type=submit]';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: Page\Edit::route('/123-post');
     */
    public static function route($param)
    {
        return static::$URL.$param;
    }


}
