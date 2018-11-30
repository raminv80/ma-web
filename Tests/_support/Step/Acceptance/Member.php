<?php
namespace Step\Acceptance;

use Page\Login as LoginPage;

class Member extends \AcceptanceTester
{

    public function loginAsMemberWithCorrectCredentials()
    {
        $I = $this;
        if ($I->loadSessionSnapshot('MemberLogin')) {
            return;
        }
        $I->amGoingTo('login as valid active member');
        $I->amOnPage(LoginPage::$URL);
        $I->fillField(LoginPage::$usernameField, getenv('MEMBER_VALID_USERNAME'));
        $I->fillField(LoginPage::$passwordField, getenv('MEMBER_VALID_PASSWORD'));
        $I->click(LoginPage::$loginButton);
        $I->wait(1);
        $I->see('Stay up to date');
        $I->saveSessionSnapshot('MemberLogin');
    }

    public function loginAsMemberWithIncorrectPassword()
    {
        $I = $this;
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
