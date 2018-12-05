<?php
namespace Step\Acceptance;

use Page\Login as LoginPage;

class Member extends \AcceptanceTester
{

    public function loginAsMemberWithCorrectCredentials()
    {
        $I = $this;
//        if ($I->loadSessionSnapshot('MemberLogin')) {
//            return;
//        }
        $I->amGoingTo('login as valid active member');
        $I->amOnPage(LoginPage::$URL);
        $I->fillField(LoginPage::$usernameField, getenv('MEMBER_VALID_USERNAME'));
        $I->fillField(LoginPage::$passwordField, getenv('MEMBER_VALID_PASSWORD'));
        $I->click(LoginPage::$loginButton);
        $I->waitForText('Member number', 10);
        $I->see(getenv('MEMBER_VALID_USERNAME'));
//        $I->saveSessionSnapshot('MemberLogin');
    }

    public function loginAsLifetimeMember()
    {
        $I = $this;
        $I->amGoingTo('login as lifetime member');
        $I->amOnPage(LoginPage::$URL);
        $I->fillField(LoginPage::$usernameField, getenv('MEMBER_LIFETIME_USERNAME'));
        $I->fillField(LoginPage::$passwordField, getenv('MEMBER_LIFETIME_PASSWORD'));
        $I->click(LoginPage::$loginButton);
        $I->waitForText('Member number');
        $I->see('Member number');
        $I->see('You are always protected');
    }

    public function loginAsMemberWithIncorrectEmail()
    {
        $I = $this;
    }

    public function loginAsUnApprovedMember()
    {
        $I = $this;
    }

    public function loginAsExpiredMember()
    {
        $I = $this;
    }

    public function loginAsSeniorMember()
    {
        $I = $this;
    }

}
