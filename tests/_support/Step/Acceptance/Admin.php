<?php
namespace Step\Acceptance;
use Page\AdminLogin as AdminLogin;

class Admin extends \AcceptanceTester
{

    public function loginAsAdmin()
    {
        $I=$this;
        if ($I->loadSessionSnapshot('AdminLogin')) {
            return;
        }
        $I->amGoingTo('login as admin');
        $I->amOnPage(AdminLogin::$URL);
        $I->fillField(AdminLogin::$usernameField, 'online@them.com.au');
        $I->fillField(AdminLogin::$passwordField, '^L106T28uSuc');
        $I->click(AdminLogin::$loginButton);
        $I->wait(1);
        $I->see('Stay up to date');
        $I->saveSessionSnapshot('AdminLogin');
    }

}
