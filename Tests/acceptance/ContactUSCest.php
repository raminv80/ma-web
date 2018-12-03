<?php 

class ContactUSCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage('/contact-us');
    }

    // tests
    public function contactUsFormSubmits(AcceptanceTester $I)
    {
        $I->scrollTo('#contact_form');
        $I->fillField('name', ' Acceptance Tester');
        $I->fillField('email', 'acceptancetester@them.com.au');
        $I->fillField('phone', '04511111111');
        $I->fillField('postcode', '5000');
        $I->fillField('membership_no', '1234');
        $I->selectOptionInSelectBoxItDropDown('nature_enquiry', 2);
        $I->fillField('#enquiry', 'THIS IS A TEST');
        $I->wait(3);//to pass spam prevention
        $I->click('#fbsub');
        $I->wait(1);
        $I->see('Thank you for contacting us.');
    }
}
