<?php
use Page\Login as LoginPage;


class LoginCest 
{    
    public function _before(AcceptanceTester $I)
    {
//        $I->amOnPage(LoginPage::$URL);
    }

//    public function memberLoginSuccessfully(AcceptanceTester $I)
//    {
//        // write a positive login test
//        $I->wantTo('login to site admin');
//        $I->amGoingTo('use correct admin credentials');
//        $I->fillField(LoginPage::$usernameField, 'online@them.com.au');
//        $I->fillField(LoginPage::$passwordField, '^L106T28uSuc');
//        $I->click(LoginPage::$loginButton);
//        $I->wait(5);
//        $I->see('Stay up to date with Them Advertising');
//    }
//
//    public function memberLoginWithInvalidPassword(AcceptanceTester $I)
//    {
//        // write a negative login test
//        $I->wantTo('login to site admin');
//        $I->amGoingTo('use incorrect credentials');
//        $I->fillField(LoginPage::$usernameField, 'davert');
//        $I->fillField(LoginPage::$passwordField, 'qwerty');
//        $I->click(LoginPage::$loginButton);
//        $I->see('Invalid membership number or password.');
//    }

    public function adminLoginSuccessfully(\Step\Acceptance\Admin $I)
    {
        // write a positive login test
        $I->wantTo('login to admin dashboard');
        $I->amGoingTo('use correct admin credentials');
        $I->loginAsAdmin();
    }
}
