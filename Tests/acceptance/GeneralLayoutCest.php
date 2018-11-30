<?php

use Page\GeneralLayout as Layout;
use Page\Login;

class GeneralLayoutCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage('/');
    }

    // tests
    public function headerLinksWork(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->click('#logo');
        $I->seeCurrentUrlEquals('/');
        $I->amOnPage('/contact-us');
        $I->click('#logo');
        $I->seeCurrentUrlEquals('/');
        $I->click(Layout::$HeaderLogin);
        $I->seeElement(Login::$loginForm);
        $I->amOnPage('/');
        $I->click(Layout::$HeaderRenew);
        $I->seeElement(Login::$loginForm);
    }

    public function mainMenuWorks(AcceptanceTester $I)
    {
        //dropdown menu
        $I->amGoingTo('test dropdown menu');
        $I->amOnPage('/contact-us');
        $I->click('#main-navbar > nav > ul > li:nth-child(1) > a');
        $I->seeCurrentUrlEquals('/');
        $I->dontSeeElement('#main-navbar > nav > ul > li:nth-child(2) > ul > li:nth-child(6) > a');
        $I->moveMouseOver('#main-navbar > nav > ul > li:nth-child(2)');
        $I->seeElement('#main-navbar > nav > ul > li:nth-child(2) > ul > li:nth-child(6) > a');
        $I->click('#main-navbar > nav > ul > li:nth-child(2) > ul > li:nth-child(6) > a');
        $I->seeCurrentUrlEquals('/refer-your-patients');
        $I->dontSeeElement('#main-navbar > nav > ul > li:nth-child(5) > ul > li:nth-child(1)');
        $I->moveMouseOver('#main-navbar > nav > ul > li:nth-child(5)');
        $I->seeElement('#main-navbar > nav > ul > li:nth-child(5) > ul > li:nth-child(1)');
        $I->click('#main-navbar > nav > ul > li:nth-child(5) > ul > li:nth-child(1)');
        $I->seeCurrentUrlEquals('/contact-us');

        //wishlist icon
        $I->amGoingTo('test wishlist button');
        $I->click('#wishlist');
        $I->seeCurrentUrlEquals('/login');

        //cart icon
        $I->amGoingTo('test cart button');
        $I->click('#cart-hover');
        $I->seeCurrentUrlEquals('/shopping-cart');
        $I->see(0, '#cart-hover > span');
        $I->moveMouseOver('#cart-hover');
        $I->see('Your shopping cart is empty.');

        //search icon
        $I->amGoingTo('test search button');
        $I->click('#sb-search > span.sb-icon-search');
        $I->seeElement('#searchbox');
        $I->fillField('#search', 'test');
        $I->click('#searchbox > input[type="image"]:nth-child(2)');
        $I->see('Search result for "test"');
        $I->click('#sb-search > span.sb-icon-search');
        $I->click('#sb-search > span.sb-icon-close > img');
        $I->dontSeeElement('#searchbox');
    }

    public function footerSubscribeWorks(AcceptanceTester $I)
    {
        $I->see('Stay up to date with MedicAlert Foundation');
        $I->seeElement('#newsl_form');
        $I->fillField('#fieldName', 'AcceptanceTester');
        $I->fillField('#fieldEmail', 'AcceptanceTester@them.com.au');
        $I->click('#newsl_form input[type=submit]');
        try{
            $I->see('Thank you for signing up');
        }catch (Exception $e) {
            $I->see('To confirm your subscription, please confirm that you are not a robot');
        }
    }

    public function footerWorks(AcceptanceTester $I)
    {
        //Need help?
        $I->dontSeeElement('#helpcont');
        $I->click('#helptab');
        $I->wait(1);
        $I->seeElement('#helpcont');

        //footer menu
        $I->click('#footmenu > li:nth-child(1) > ul > li:nth-child(1) > a');
        $I->seeCurrentUrlEquals('/benefits-of-membership');
        $I->click('#footmenu > li:nth-child(5) > ul > li:nth-child(5) > a');
        $I->seeCurrentUrlEquals('/accessibility');

        //socials
        $I->seeElement('#socfooter1 > div:nth-child(2) > a');

        //T&C
        $I->click('#mobbot > div > div > div.col-sm-9 > a:nth-child(1)');
        $I->seeCurrentUrlEquals('/privacy-policy');
        $I->click('#mobbot > div > div > div.col-sm-9 > a:nth-child(2)');
        $I->seeCurrentUrlEquals('/terms-and-conditions');
    }
}
