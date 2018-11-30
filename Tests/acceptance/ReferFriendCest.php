<?php 

class ReferFriendCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage('/refer-a-friend');
    }

    // tests
    public function referAFriendWorks(AcceptanceTester $I)
    {
        $I->amGoingTo('fill up the form');
        $I->fillField('#name', 'Acceptance Tester');
        $I->fillField('#email', 'acceptancetester@them.com.au');
        $I->fillField('#memberno', '12345');
        $I->fillField('#frname', 'codecption');
        $I->fillField('#fremail', 'acceptance.tester@them.com.au');
        $I->click('#fbsub');
        $I->see('Thank you for referring a friend.');
    }
}
