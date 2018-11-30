<?php 

class giftCertificateCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage('/gift-certificates');
    }

    // tests
    public function tryToAddGift35(AcceptanceTester $I)
    {
        $I->wantTo('Purchase a gift certificate');
        $I->dontSeeElement('#remail');
        $I->click('#gift_form > div:nth-child(4) > div:nth-child(1) > div > label > div > div.giftoptext');
        $I->seeElement('#remail');
        $I->amGoingTo('fill up gift form');
        $I->fillField('#rname', 'AcceptanceTester');
        $I->fillField('#remail', 'AcceptanceTester@them.com.au');
        $I->fillField('#sname', 'AcceptanceTester@them.com.au');
        $I->fillField('#semail', 'AcceptanceTester@them.com.au');
        $I->click('#fields-wrapper > div > div:nth-child(4) > div > label');
        $I->fillField('#message', 'Acceptance Tester by Them Advertising');
        $I->dontSeeElement('#sendday');
        $I->click('#fields-wrapper > div > div:nth-child(6) > div > div:nth-child(2) > label');
        $I->seeElement('#sendday');
        $I->click('#fields-wrapper > div > div:nth-child(6) > div > div:nth-child(1) > label');
        $I->dontSeeElement('#sendday');
        #successful payment
        $I->amGoingTo('use valid payment');
        $I->fillField('#ccno', '4564710000000004');
        $I->fillField('#ccname', 'AcceptanceTester');
        $I->selectOption('#ccmonth', '2 - Feb');
        $I->selectOption('#ccyear', '2019');
        $I->fillField('#cccsv', '847');
        $I->click('#fields-wrapper > div > div.row.text-left > div > div:nth-child(1) > label');
        $I->amGoingTo('submit the form');
        $I->click('#fbsub');
        $I->seeCurrentUrlEquals('/thank-you-for-purchasing-a-gift-certificate');
        $I->see('Thank you for purchasing a gift certificate');
    }
}
