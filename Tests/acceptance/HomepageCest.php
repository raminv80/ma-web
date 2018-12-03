<?php 

class HomepageCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage('/');
    }

    // tests
    public function heroSectionWorks(AcceptanceTester $I)
    {
        $I->click('#learnmorehome');
        $I->seeCurrentUrlEquals('/how-to-become-a-member');
    }

    public function offersSectionWorks(AcceptanceTester $I)
    {
        $I->scrollTo('#shoptherange');
        $I->click('#shoptherange');
        $I->seeCurrentUrlEquals('/offers');
    }

    public function memberBenefitsSectionWorks(AcceptanceTester $I)
    {
        $I->click('a[title="Learn more about MedicAlert membership"]');
        $I->seeCurrentUrlEquals('/benefits-of-membership');
    }

    public function productSectionWorks(AcceptanceTester $I){
        $I->click('#categorycontainer > div.col-sm-6 > div > a > div.imgcont > img');
        $I->seeCurrentUrlEquals('/products/women');
        $I->amOnPage('/');
        $I->click('#whiteblock2 > div > div:nth-child(3) > div > a');
        $I->seeCurrentUrlEquals('/products');
    }
}
