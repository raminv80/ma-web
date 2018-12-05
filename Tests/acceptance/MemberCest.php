<?php namespace acceptance;
use Helper\Acceptance;
use Page\Login as LoginPage;

class MemberCest
{
    public function _before(\Step\Acceptance\Member $I)
    {
    }

    // tests
    public function membership(\Step\Acceptance\Member $I)
    {
        $I->expectTo('be able to login with my membership details');
        $I->loginAsMemberWithCorrectCredentials();

    }

    public function checkoutAsMember(\Step\Acceptance\Member $I){
        $I->loginAsMemberWithCorrectCredentials();
        $I->expectTo('Checkoiut should not ask me to login');

        $I->amOnPage( '/products/all-products' );
        $I->wait(2);
        $I->click( '#poi-740 > div > a > img' );
        $I->seeCurrentUrlEquals('/stainless-steel-ball-chain-necklace');
        $I->wait( 2 );
        $I->selectOption( '#length', '76' );
        $I->selectOption( '#medical_id_size', '90' );
        $I->scrollTo('.variant-addbtns');
        $I->click('.variant-addbtns');
        $I->wait( 1 );
        $I->click( '#cart-hover' );
        $I->seeCurrentUrlEquals( '/shopping-cart' );
        $I->scrollTo('#cart-continue-btn');
        $I->click('#cart-continue-btn');
        $I->amOnPage('/checkout');
        $I->see('Delivery', 'h1.checkout2');
        $I->see('Billing details');
        $I->submitCheckoutBillingForm();
        $I->expectTo('go to payment step of checkout');
        $I->wait(2);
        $I->see('Payment', 'h1.checkout3');
        $I->submitCheckoutPaymentForm();
        $I->expectTo('go to thank you page');
        $I->seeCurrentUrlEquals('/thank-you-for-purchasing');
        $I->see('Thank you for purchasing');
        $I->see('Your order ID is');

        $I->amGoingTo('visit my purchases page');
        $I->amOnPage('/my-purchases');
        $I->expectTo('see my recent purchased item');
        $I->wait(1);
        $I->see('Stainless Steel Ball Chain Necklace');
    }

    public function updatePassword(\Step\Acceptance\Member $I){
        $I->loginAsMemberWithCorrectCredentials();
        $I->scrollTo('#account');
        $I->dontSeeElement('#pass_form');
        $I->click('Update my password');
        $I->wait(1);
        $I->seeElement('#pass_form');
        $I->scrollTo('#pass_form');
        $I->fillField('Current Password', getenv('MEMBER_VALID_PASSWORD'));
        $I->fillField('New Password', 'password');
        $I->click('Update password');
        $I->wait(1);
        $I->see('Must include at least one upper case character');
        $I->fillField('#newpassword', 'passwordPASSWORD123!');
        $I->click('Update password');
        $I->wait(2);
        $I->see('Your password has been updated.');

        $I->amGoingTo('logout and login with new credentials');
        $I->scrollTo('#headgrey');
        $I->click('Log out');
        $I->amOnPage(LoginPage::$URL);
        $I->scrollTo('#login-form');
        $I->fillField(LoginPage::$usernameField, getenv('MEMBER_VALID_USERNAME'));
        $I->fillField(LoginPage::$passwordField, getenv('MEMBER_VALID_PASSWORD'));
        $I->click(LoginPage::$loginButton);
        $I->wait(2);
        $I->see('Sorry, your password was incorrect.');
        $I->fillField(LoginPage::$passwordField, 'passwordPASSWORD123!');
        $I->click(LoginPage::$loginButton, '#login-form');
        $I->wait(2);
        $I->seeCurrentUrlEquals('/my-account');

        $I->amGoingTo('revert password back to original');
        $I->scrollTo('#account');
        $I->click('Update my password');
        $I->wait(1);
        $I->scrollTo('#pass_form');
        $I->fillField('Current Password', 'passwordPASSWORD123!');
        $I->fillField('New Password', getenv('MEMBER_VALID_PASSWORD'));
        $I->click('Update password');
        $I->wait(2);
        $I->see('Your password has been updated.');
    }

    private function updateProfile(\Step\Acceptance\Member $I) {
        //After a profile update account updates are locked till approval on membership system.
        //Because this function relies on approval on membership system and can't be automated for now.
        $I->loginAsMemberWithCorrectCredentials();
        $I->scrollTo('#renewal');
        $I->click('View / update your profile');
        $I->seeCurrentUrlEquals('/update-my-profile');
        $I->scrollTo('#middlename');
        $I->fillField('#middlename', 'Acceptance Tester');
        $I->checkOption('profile-confirmation');
        $I->scrollTo('#submit-btn');
        $I->click('Save my changes and verify');
        $I->see('Your updates are currently pending.');
    }

    public function lifeTimeMember(\Step\Acceptance\Member $I){
        $I->loginAsLifetimeMember();

        $I->amGoingTo('order membership card');
        $I->scrollTo('#account');
        $I->click('Order membership card');
        $I->wait(1);
        $I->seeCurrentUrlEquals('/shopping-cart');
        $I->scrollTo('#cartmain');
        $I->see('Member Service Fee - Membership card');
        $I->scrollTo('#total');
        $I->see('$8.00', '#total');
    }
}
