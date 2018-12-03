<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

   /**
    * Define custom actions here
    */

    public function selectOptionInSelectBoxItDropDown($selectId, $nthOption){
        $I=$this;
        $I->waitForElementVisible('#'.$selectId.'SelectBoxItContainer');
        $I->scrollTo('#'.$selectId.'SelectBoxItContainer');
        $I->click('#'.$selectId.'SelectBoxItContainer');
        $I->wait(1);
        $I->click('#'.$selectId.'SelectBoxItOptions > li:nth-child('.$nthOption.') > a');
    }

    public function submitSignupForm(){
        $time=time();
        $I=$this;
        //$I->amOnPage('/login-register');
        $I->amGoingto('enter my personal details to create an account');
        $I->fillField('gname', 'Acceptancce');
        $I->fillField('surname', 'Tester');
        $I->fillField('#dob', '10/01/2000');
        $I->pressKey('#dob', WebDriverKeys::ENTER);
        $I->selectOptionInSelectBoxItDropDown('gender', 2);
        $I->fillField('address', 'test');
        $I->fillField('suburb', 'Adelaide');
        $I->selectOptionInSelectBoxItDropDown('state', 5);
        $I->fillField('postcode', '5000');
        $I->fillField('mobile', '0400000000');
        $I->fillField('#reg-email', "acceptanceTester-$time@them.com.au");
        $I->fillField('confirm_email', "acceptanceTester-$time@them.com.au");
        $I->fillField('password', 'password123TEST');
        $I->selectOptionInSelectBoxItDropDown('hearabout', 2);
        $I->checkOption('agree');
        $I->scrollTo('#signup-submit');
        $I->click('#signup-submit');
    }

    public function submitCheckoutBillingForm(){
        $I=$this;
        $time=time();
        $I->amGoingTo('enter by billing details');
        $I->fillField('#name1', 'Acceptancce');
        $I->fillField('#surname1', 'Tester');
        $I->fillField('#street', 'test');
        $I->fillField('#suburb', 'Adelaide');
        $I->fillField('#suburb', 'Adelaide');
        $I->selectOptionInSelectBoxItDropDown('state', 5);
        $I->fillField('#postcode-field', '5000');
        $I->fillField('#email', "acceptanceTester-$time@them.com.au");
        $I->fillField('#phone', '0400000000');
        $I->checkOption('#chksame');
        $I->scrollTo('#submit-billing');
        $I->click('#submit-billing');
    }

    public function submitCheckoutPaymentForm(){
        $I=$this;
        $I->fillField('Card number', '4564710000000004');
        $I->fillField("Cardholder's name", 'Acceptance Tester');
        $I->selectOption('#ccmonth', '02');
        $I->selectOption('#ccyear', '2019');
        $I->fillField('Security code', 847);
        $I->uncheckOption('#autorenewal');
        $I->click('#payment-btn');
    }
}
