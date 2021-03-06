<?php


class CheckoutCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function NormalCheckoutAsGuest(AcceptanceTester $I) {
        $time=time();

        #Add a product to cart
        $I->amOnPage( '/products/all-products' );
        $I->wantTo( 'purchase products as guest' );
        $I->expectTo('see cart is empty');
        $I->see( 0, '#cart-hover > span' );
        $I->wait( 2 );

        $I->amGoingTo('view Stainless Steel Pendant & Curb Chain product');
        $I->click( '#products-wrapper a[title="Stainless Steel Pendant & Curb Chain"] img' );
        $I->seeCurrentURLEquals( '/stainless-steel-pendant-and-curb-chain' );

        $I->amGoingTo( 'add this product to cart' );
        $I->click( 'Add to Cart' );
        $I->see( 'This field is required.' );
        $I->click( 'label[for="colour_stainless_steel_cherry"]' );
        $I->selectOption( '#length', '74' );
        $I->selectOption( '#medical_id_size', '87' );
        $I->scrollTo('.prodaddcart');
        $I->click( 'Add to Cart' );
        $I->wait( 3 );

        $I->expectTo('see cart status is updated to show there is 1 product in cart');
        $I->scrollTo('#cart-hover');
        $I->see( 1, '#cart-hover > span' );

        #Membership automatically is added to cart
        $I->expectTo( 'see membership product automatically is added to cart' );
        $I->click( '#cart-hover' );
        $I->seeCurrentUrlEquals( '/shopping-cart' );
        $I->see( 2, '#cart-hover > span' );
        $I->see( 'Stainless Steel Pendant & Curb Chain' );
        $I->see( 'Member Service Fee - '.date('Y') );
        $I->wait( 1 );
        $I->see( '$101.00', '#subtotal' );
        $I->see( '$4.45', '#GST' );
        $I->see( '$110.50', '#total' );

        $I->amGoingTo( 'change quantity amount' );
        $I->expectTo( 'see total price updates' );
        $I->selectOptionInSelectBoxItDropDown('quantity', 2);
        $I->scrollTo( '#subtotal' );
        $I->wait( 1 );
        $I->see( '$153.00', '#subtotal' );
        $I->see( '$4.45', '#GST' );
        $I->see( '$162.50', '#total' );

        #Use voucher
        $I->amGoingTo('apply stainless-steel voucher code');
        $I->fillField('#promo', 'STAINLESS-STEEL');
        $I->click('#discount-form a.btn-red');
        $I->wait(1);
        $I->see("'Stainless Steel' has been successfully applied.");
        $I->amGoingTo('Add a stainless steel discounted product');
        $I->amOnPage('/products/stainless-steel');
        $I->wait(1);
        $I->click('#products-wrapper a[title="Stainless Steel Expanda Band Bracelet"] img');
        $I->seeCurrentUrlEquals('/stainless-steel-expanda-band-bracelet');
        $I->wait(2);
        $I->click('label[for="colour_stainless_steel_rose"]');
        $I->selectOption('#length', '47');
        $I->selectOption('#medical_id_size', '11');
        $I->wait(2);
        $I->scrollTo('.prodaddcart');
        $I->click('Add to Cart');

        $I->expectTo('receive discount');
        $I->click('#cart-hover');
        $I->seeCurrentUrlEquals('/shopping-cart');
        $I->scrollTo('#subtotal');
        $I->wait(1);
        $I->see('-$22.00', '#discount');
        $I->see('$197.50', '#total');

        #checkout
        $I->amGoingTo('checkout');
        $I->scrollTo('#total');
        $I->see('CONTINUE');
        $I->click('#cart-continue-btn');
        $I->seeCurrentUrlEquals('/login-register');
        $I->submitSignupForm();
        $I->expectTo('goto delivery step of checkout');
        $I->wait(4);
        $I->waitForText('Delivery');
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
    }
}
