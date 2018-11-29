<?php 

class productsPageCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function productLandingpageLinksWork(AcceptanceTester $I)
    {
        $I->amOnPage('/products');
        $I->click('#categorycontainer > div:nth-child(2) > div > a > div.imgcont > img');
        $I->seeCurrentUrlEquals('/products/men');

        $I->amOnPage('/products');
        $I->wait(1);
        $I->scrollTo('#popslide');
        $I->see('Stainless Steel Nylon Fabric Sports Band');
        $I->click('#popslide > div > ul > li > div > a > img');
        $I->seeCurrentUrlEquals('/stainless-steel-nylon-fabric-sports-band');

        $I->amOnPage('/products');
        $I->click('#prolist > div:nth-child(2) > div > a');
        $I->seeCurrentUrlEquals('/products/all-products');

        $I->amOnPage('/products');
        $I->see('More than just medical identification jewellery');
        $I->click('#categ-bot > div > div > div > a');
        $I->seeCurrentUrlEquals('/benefits-of-membership');
    }

    public function ProductListingWorks(AcceptanceTester $I) {
        $I->amOnPage( '/products/all-products' );
        //all products are shown
        $I->see('Shop ALL PRODUCTS');
        $I->see('64 products');
        $I->seeNumberOfElements( '#products-wrapper div.prodout', 64 );

        #product links work
        $I->amGoingTo( 'click on a product' );
        $I->click( '#products-wrapper div:first-child a img' );
        $I->cantSeeCurrentUrlEquals( '/products/all-products' );

        //sort by
        $I->amOnPage( '/products/all-products' );
        $I->see( '$35', '#products-wrapper div:first-child .prod-price' );
        $I->click( '#sortSelectBoxIt' );
        $I->click( '#sortSelectBoxItOptions li[data-val="price-high-low"]' );
        $I->see( '$2,230', '#products-wrapper div:first-child .prod-price' );
    }

    public function ProductsFilterWorks(AcceptanceTester $I){
        //product filter
        $I->amOnPage('/products/all-products');
        $I->see('Women');
        $I->see('special occasion');
        $I->see('Kids');
        $I->click('#accordion1 > h4 > a > i');
        $I->wait(1);
        $I->dontSee('Women');
        $I->dontSee('special occasion');
        $I->dontSee('Kids');
        $I->click('#accordion1 > h4 > a > i');
        $I->wait(1);
        $I->see('Women');
        $I->see('special occasion');
        $I->see('Kids');
        $I->click('Women');
        $I->seeCurrentUrlEquals('/products/women');
        $I->see('shop WOMEN');
        $I->see('53 products');
        $I->wait(1);
        $I->click('#type > div > div:nth-child(1) > label');
        $I->wait(1);
        $I->see('38 products');
        $I->click('#type > div > div:nth-child(1) > label');
        $I->wait(1);
        $I->see('53 products');
    }
}
