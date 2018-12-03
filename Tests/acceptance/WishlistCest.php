<?php

class WishlistCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function wishlistRequiresAuthenticatedUser(AcceptanceTester $I)
    {
        $I->amGoingTo('As guest add a product to wishlist');
        $I->amOnPage('/products/all-products');
        $I->wait( 2 );
        $I->scrollTo('#poi-562');
        $I->click( '#poi-562 .prod-wishlist a > img' );
        $I->expectTo('be redirected to login page');
        $I->wait( 1 );
        $I->seeCurrentUrlEquals('/login');
    }

    public function addToWishlistWorks(\Step\Acceptance\Member $I){
        $I->amGoingTo('As logged in member add a product to wishlist');
        $I->loginAsMemberWithCorrectCredentials();
        $I->amOnPage('/products/all-products');
        $I->wait( 2 );
        $I->scrollTo('#poi-740');
        $I->click( '.prodwishlist.prodwishlist-740 > img' );
        $I->wait(2);
        $I->seeElement('.prodwishlist.prodwishlist-740.active');

        $I->expectTo('see the product added to my wishlist');
        $I->click('#wishlist');
        $I->seeCurrentUrlEquals('/my-wish-list');
        $I->wait( 2 );
        $I->see('Stainless Steel Ball Chain Necklace');

        $I->amGoingTo('remove a product form wishlist');
        $I->click('#poi-740 div.prod-wishlist > a > img');
        $I->wait(1);
        $I->dontSee('Stainless Steel Ball Chain Necklace');
        $I->amOnPage('/products/all-products');
        $I->dontSeeElement('.prodwishlist.prodwishlist-740.active');
    }
}
