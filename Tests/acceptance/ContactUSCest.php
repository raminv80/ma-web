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
        $I->fillField('name', ' Acceptance Tester');
        $I->fillField('email', 'acceptancetester@them.com.au');
        $I->fillField('phone', '04511111111');
        $I->fillField('postcode', '5000');
        $I->fillField('phone', '04001111111');
        $I->fillField('membership_no', '1234');
        $I->fillField('enquiry', 'THIS IS A TEST');
        $I->see('Thank you for contacting us.');
    }
}
