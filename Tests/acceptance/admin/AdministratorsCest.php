<?php namespace acceptance\admin;
use Codeception\Util\Locator;

class AdministratorsCest
{
    public function _before(\Step\Acceptance\Admin $I)
    {
    }

    // tests
    public function adminDashboard(\Step\Acceptance\Admin $I)
    {
        $I->loginAsAdmin();
        $I->click('Menu');
        $I->see('System');
        $I->see('Content');
        $I->see('Settings');
        $I->see('E-commerce');
        $I->see('Conversions');
        $I->see('Logout');
    }

    public function manageAdmins(\Step\Acceptance\Admin $I){
        $I->loginAsAdmin();
        $I->click('Menu');
        $I->click('Administrators');
        $I->seeCurrentUrlEquals('/admin/list/administrator');
        $I->see('Them Digital');

        $I->amGoingTo('create a new admin');
        $I->click('Add New');
        $I->canSeeInCurrentUrl('/admin/edit/administrator');
        $I->see('New Admin');
        $I->fillField('#admin_name', 'Acceptance');
        $I->fillField('#admin_surname', 'Tester');
        $I->fillField('#admin_email', 'acceptance@them.com.au');
        $I->fillField('#password', 'passwordPASSWORD123!@#');
        $I->click('Save');
        $I->wait(1);
        $I->amOnPage('/admin/list/administrator');
        $I->see('Acceptance');

        $I->amGoingTo('Edit admin user');
        $I->click('tbody > tr:nth-child(2) > td:nth-child(3) > a');
        $I->fillField('#admin_name', 'Acceptance Tester');
        $I->click('Save');
        $I->amOnPage('/admin/list/administrator');
        $I->wait(1);
        $I->see('Acceptance Tester');

        $I->amGoingTo('remove admin user');
        $I->click('table > tbody > tr:nth-child(2) > td:nth-child(4) > a');
        $I->acceptPopup();
        $I->wait(2);
        $I->dontSee('Acceptance Tester');
    }

    public function managePages(\Step\Acceptance\Admin $I){
        $I->loginAsAdmin();
        $I->amOnPage('/admin/list/page');
        $I->amGoingTo('create a new page');
        $I->click('Add New');
        $I->seeCurrentUrlEquals('/admin/edit/page');
        $I->checkOption('#id_listing_parent_flag');
        $I->fillField('#id_listing_name', 'Acceptance Test');
        $I->fillField('#id_listing_seo_title', 'acceptance test');
        $I->wait(1);
        $I->see('acceptance-test', '#id_listing_url_text');
        $I->fillCkEditorById('id_listing_content1', 'test content 1');
        $I->fillCkEditorById('id_listing_content2', 'test content 2');
        $I->fillCkEditorById('id_listing_content3', 'test content 3');
        $I->fillCkEditorById('id_listing_content4', 'test content 4');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->amOnPage('/admin/list/page');
        $row = Locator::contains('tr', 'Acceptance Test');
        $I->scrollTo($row);
        $I->see('Acceptance Test');

        $I->expectTo('see a preview');
        $I->amOnPage('/draft/acceptance-test');
        $I->see('Acceptance Test', 'h1');
        $I->see('test content 1');

        $I->amOnPage('/admin/list/page');
        $I->scrollTo($row);
        $I->amGoingTo('update the page');
        $I->click('Edit', $row);
        $I->fillCkEditorById('id_listing_content1', 'test content 1 edited');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->expectTo('see the update in preview');
        $I->amOnPage('/draft/acceptance-test');
        $I->see('test content 1 edited');

        $I->amOnPage('/admin/list/page');
        $I->scrollTo($row);
        $I->amGoingTo('delete the page');
        $I->click('Delete', $row);
        $I->acceptPopup();
        $I->seeCurrentUrlEquals('/admin/list/page');
        $I->expectTo('not see record of the page anymore');
        $I->dontSeeInPageSource('Acceptance Test');
    }

    public function manageLandingPage(\Step\Acceptance\Admin $I){
        $I->loginAsAdmin();
        $I->amOnPage('/admin/list/landing-page');
        $I->amGoingTo('Create a landing page');
        $I->click('Add New');
        $I->fillField('#id_listing_name', 'Acceptance Test');
        $I->fillField('#id_listing_seo_title', 'acceptance test');
        $I->wait(1);
        $I->see('acceptance-test', '#id_listing_url_text');
        $I->fillCkEditorById('id_listing_content1', 'test content 1');
        $I->fillCkEditorById('id_listing_content2', 'test content 2');
        $I->fillCkEditorById('id_listing_content3', 'test content 3');
        $I->fillCkEditorById('id_listing_content4', 'test content 4');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->amOnPage('/admin/list/landing-page');
        $row = Locator::contains('tr', 'Acceptance Test');
        $I->scrollTo($row);
        $I->see('Acceptance Test');

        $I->expectTo('see a preview');
        $I->amOnPage('/draft/acceptance-test');
        $I->see('Acceptance Test', 'h1');
        $I->see('test content 1');

        $I->amOnPage('/admin/list/landing-page');
        $I->scrollTo($row);
        $I->amGoingTo('update the landing page');
        $I->click('Edit', $row);
        $I->fillCkEditorById('id_listing_content1', 'test content 1 edited');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->expectTo('see the update in preview');
        $I->amOnPage('/draft/acceptance-test');
        $I->see('test content 1 edited');

        $I->amOnPage('/admin/list/landing-page');
        $I->scrollTo($row);
        $I->amGoingTo('delete the page');
        $I->click('Delete', $row);
        $I->acceptPopup();
        $I->amOnPage('/admin/list/landing-page');
        $I->expectTo('not see record of the page anymore');
        $I->dontSeeInPageSource('Acceptance Test');
    }

    public function manageCompetitionPage(\Step\Acceptance\Admin $I){
        $I->loginAsAdmin();
        $I->amOnPage('/admin/list/competition');
        $I->amGoingTo('Create a competition page');
        $I->click('Add New');
        $I->fillField('#id_listing_name', 'Acceptance Test');
        $I->fillField('#id_listing_seo_title', 'acceptance test');
        $I->wait(1);
        $I->see('acceptance-test', '#id_listing_url_text');
        $I->fillCkEditorById('id_listing_content1', 'test content 1');
        $I->fillCkEditorById('id_listing_content2', 'test content 2');
        $I->fillCkEditorById('id_listing_content4', 'test content 4');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->amOnPage('/admin/list/competition');
        $row = Locator::contains('tr', 'Acceptance Test');
        $I->scrollTo($row);
        $I->see('Acceptance Test');

        $I->expectTo('see a preview');
        $I->amOnPage('/draft/acceptance-test');
        $I->see('Acceptance Test', 'h1');
        $I->see('test content 1');

        $I->amOnPage('/admin/list/competition');
        $I->scrollTo($row);
        $I->amGoingTo('update the competition');
        $I->click('Edit', $row);
        $I->fillCkEditorById('id_listing_content1', 'test content 1 edited');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->expectTo('see the update in preview');
        $I->amOnPage('/draft/acceptance-test');
        $I->see('test content 1 edited');

        $I->amOnPage('/admin/list/competition');
        $I->scrollTo($row);
        $I->amGoingTo('delete the page');
        $I->click('Delete', $row);
        $I->acceptPopup();
        $I->amOnPage('/admin/list/competition');
        $I->expectTo('not see record of the page anymore');
        $I->dontSeeInPageSource('Acceptance Test');
    }

    public function manageMenu(\Step\Acceptance\Admin $I){
        $I->loginAsAdmin();
        $I->amOnPage('/admin/list/menu');
        $I->amGoingTo('Create a menu');
        $I->click('Add New');
        $I->fillField('#menu_name', 'Acceptance Test');
        $I->wait(2);
        $I->selectOption('#menu_parent_id', '11');
        $I->selectOption('#menu_listing_id', '2');;
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->amOnPage('/admin/list/menu');
        $row = Locator::contains('tr', 'Acceptance Test');
        $I->scrollTo($row);
        $I->see('- Acceptance Test');

        $I->expectTo('see menu is updated');
        $I->amOnPage('/');
        $I->moveMouseOver('#main-navbar > nav > ul > li:nth-child(5)');
        $I->seeElement('#main-navbar > nav > ul > li:nth-child(5) > ul > li:nth-child(6)');
        $I->see('Acceptance Test');
        $I->click('#main-navbar > nav > ul > li:nth-child(5) > ul > li:nth-child(6)');
        $I->seeCurrentUrlEquals('/about-medicalert-foundation');

        $I->amOnPage('/admin/list/menu');
        $I->scrollTo($row);
        $I->amGoingTo('update the menu');
        $I->click('Edit', $row);
        $I->wait(2);
        $I->selectOption('#menu_listing_id', '111');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');

        $I->expectTo('see the update in menu');
        $I->amOnPage('/');
        $I->moveMouseOver('#main-navbar > nav > ul > li:nth-child(5)');
        $I->see('Acceptance Test');
        $I->click('Acceptance Test');
        $I->seeCurrentUrlEquals('/accessibility');

        $I->amOnPage('/admin/list/menu');
        $I->scrollTo($row);
        $I->amGoingTo('delete the menu');
        $I->click('Delete', $row);
        $I->acceptPopup();
        $I->amOnPage('/admin/list/menu');
        $I->expectTo('not see record of the menu anymore');
        $I->dontSeeInPageSource('Acceptance Test');
    }

    public function manageNews(\Step\Acceptance\Admin $I){
        $I->loginAsAdmin();
        $I->amOnPage('/admin/list/news-article');
        $I->amGoingTo('Create a new news article');
        $I->click('Add New');

        $I->fillField('#id_listing_name', 'Acceptance Test');
        $I->fillField('#id_listing_seo_title', 'acceptance test');
        $I->wait(1);
        $I->see('acceptance-test', '#id_listing_url_text');
        $I->fillField('#id_listing_content1', 'test content 1');
        $I->fillCkEditorById('id_listing_content2', 'test content 2');
        $I->click('Categories');
        $I->wait(1);
        $I->checkOption('//*[@id="news_category_id_0"]');
        $I->scrollTo('#Edit_Record');
        $I->click('Save & Publish');
        $I->wait(1);
        $I->amOnPage('/admin/list/news-article');
        $row = Locator::contains('tr', 'Acceptance Test');
        $I->scrollTo($row);
        $I->see('Acceptance Test');

        $I->expectTo('see news listing is updated');
        $I->amOnPage('/news-and-resources');
        $I->seeInPageSource('Acceptance Test');
        $I->amOnPage('/news-and-resources/acceptance-test');
        $I->see('Acceptance Test', 'h1');

        $I->amOnPage('/admin/list/news-article');
        $I->wait(1);
        $I->scrollTo($row);
        $I->amGoingTo('update the news article');
        $I->click('Edit', $row);
        $I->wait(2);
        $I->fillCkEditorById('id_listing_content2', 'test content 2 edited');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');

        $I->expectTo('see the update in news article');
        $I->amOnPage('/news-and-resources/acceptance-test');
        $I->see('test content 2 edited');

        $I->amOnPage('/admin/list/news-article');
        $I->scrollTo($row);
        $I->amGoingTo('delete the news article');
        $I->click('Delete', $row);
        $I->acceptPopup();
        $I->amOnPage('/admin/list/news-article');
        $I->expectTo('not see record of the news article anymore');
        $I->dontSeeInPageSource('Acceptance Test');
    }

    public function manageNewsCategory(\Step\Acceptance\Admin $I){
        $I->loginAsAdmin();
        $I->amOnPage('/admin/list/news-cats');
        $I->amGoingTo('Create a news category');
        $I->click('Add New');
        $I->fillField('#news_category_name', 'Acceptance Test');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->amOnPage('/admin/list/news-cats');
        $row = Locator::contains('tr', 'Acceptance Test');
        $I->scrollTo($row);
        $I->see('Acceptance Test');

        $I->amGoingTo('update the category');
        $I->click('Edit', $row);
        $I->fillField('#news_category_name', 'Acceptance Test updated');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->expectTo('see the update');
        $I->amOnPage('/admin/list/news-cats');
        $I->scrollTo($row);
        $I->see('Acceptance Test updated');

        $I->amGoingTo('delete the news category');
        $I->click('Delete', $row);
        $I->acceptPopup();
        $I->amOnPage('/admin/list/news-cats');
        $I->expectTo('not see record of the news category anymore');
        $I->dontSeeInPageSource('Acceptance Test');
    }

    public function manageVideos(\Step\Acceptance\Admin $I){
        $I->loginAsAdmin();
        $I->amOnPage('/admin/list/videos');
        $I->amGoingTo('Create a news video');
        $I->click('Add New');
        $I->fillField('#id_listing_name', 'Acceptance Test');
        $I->fillField('#id_listing_seo_title', 'acceptance test');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->amOnPage('/admin/list/videos');
        $row = Locator::contains('tr', 'Acceptance Test');
        $I->scrollTo($row);
        $I->see('Acceptance Test');

        $I->amGoingTo('update the video');
        $I->click('Edit', $row);
        $I->fillField('#id_listing_name', 'Acceptance Test updated');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->expectTo('see the update');
        $I->amOnPage('/admin/list/videos');
        $I->scrollTo($row);
        $I->see('Acceptance Test updated');

        $I->amGoingTo('delete the video');
        $I->click('Delete', $row);
        $I->acceptPopup();
        $I->amOnPage('/admin/list/videos');
        $I->expectTo('not see record of the news category anymore');
        $I->dontSeeInPageSource('Acceptance Test');
    }

    public function manageTestimonials(\Step\Acceptance\Admin $I){
        $I->loginAsAdmin();
        $I->amOnPage('/admin/list/testimonials');
        $I->amGoingTo('Create a news testimonial');
        $I->click('Add New');
        $I->fillField('#id_listing_name', 'Acceptance Test');
        $I->fillField('#id_listing_seo_title', 'acceptance test');
        $I->fillField('#id_listing_content2', 'test content');
        $I->fillCkEditorById('id_listing_content3', 'test content 2');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->amOnPage('/admin/list/testimonials');
        $row = Locator::contains('tr', 'Acceptance Test');
        $I->scrollTo($row);
        $I->see('Acceptance Test');

        $I->amGoingTo('update the testimonial');
        $I->click('Edit', $row);
        $I->fillField('#id_listing_content2', 'test content updated');
        $I->scrollTo('#Edit_Record');
        $I->click('Save & Publish');
        $I->expectTo('see the update');
        $I->amOnPage('/admin/list/testimonials');
        $I->scrollTo($row);
        $I->see('Acceptance Test');

        $I->amOnPage('/testimonials');
        $I->expectTo('see the testimonial');
        $I->seeInPageSource('Acceptance Test');
        $I->seeInPageSource('test content updated');

        $I->amOnPage('/admin/list/testimonials');
        $I->amGoingTo('delete the testimonial');
        $I->click('Delete', $row);
        $I->acceptPopup();
        $I->amOnPage('/admin/list/testimonials');
        $I->expectTo('not see record of the testimonial anymore');
        $I->dontSeeInPageSource('Acceptance Test');
    }

    public function manageBannerAds(\Step\Acceptance\Admin $I){
        $I->loginAsAdmin();
        $I->amOnPage('/admin/list/banner-ads');
        $I->amGoingTo('Create a new banner-ads');
        $I->click('Add New');
        $I->fillField('#banner_name', 'Acceptance Test');
        $I->fillField('#banner_link', 'acceptance test');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->amOnPage('/admin/list/banner-ads');
        $row = Locator::contains('tr', 'Acceptance Test');
        $I->scrollTo($row);
        $I->see('Acceptance Test');

        $I->amGoingTo('update the banner-ads');
        $I->click('Edit', $row);
        $I->fillField('#banner_name', 'Acceptance Test updated');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->expectTo('see the update');
        $I->amOnPage('/admin/list/banner-ads');
        $I->scrollTo($row);
        $I->see('Acceptance Test updated');

        $I->amGoingTo('delete the banner_name');
        $I->click('Delete', $row);
        $I->acceptPopup();
        $I->amOnPage('/admin/list/banner-ads');
        $I->expectTo('not see record of the banner ad anymore');
        $I->dontSeeInPageSource('Acceptance Test');
    }

    public function manageAttributes(\Step\Acceptance\Admin $I){
        $I->loginAsAdmin();
        $I->amOnPage('/admin/list/attributes');
        $I->amGoingTo('Create an attributes');
        $I->click('Add New');
        $I->fillField('#attribute_name', 'Acceptance Test');
        $I->fillField('#attribute_description', 'acceptance test');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->amOnPage('/admin/list/attributes');
        $row = Locator::contains('tr', 'Acceptance Test');
        $I->scrollTo($row);
        $I->see('Acceptance Test');

        $I->amGoingTo('update the attribute');
        $I->click('Edit', $row);
        $I->fillField('#attribute_name', 'Acceptance Test updated');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->expectTo('see the update');
        $I->amOnPage('/admin/list/attributes');
        $I->scrollTo($row);
        $I->see('Acceptance Test updated');

        $I->amGoingTo('delete the attribute');
        $I->click('Delete', $row);
        $I->acceptPopup();
        $I->amOnPage('/admin/list/attributes');
        $I->expectTo('not see record of the attribute');
        $I->dontSeeInPageSource('Acceptance Test');
    }

    public function manageSchemas(\Step\Acceptance\Admin $I){
        $I->loginAsAdmin();
        $I->amOnPage('/admin/list/schemas');
        $I->amGoingTo('Create a schema');
        $I->click('Add New');
        $I->scrollTo('#Edit_Record');
        $I->fillField('#producttype_name', 'Acceptance Test');
        $I->fillField('#producttype_description', 'acceptance test');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->amOnPage('/admin/list/schemas');
        $row = Locator::contains('tr', 'Acceptance Test');
        $I->scrollTo($row);
        $I->see('Acceptance Test');

        $I->amGoingTo('update the schema');
        $I->click('Edit', $row);
        $I->fillField('#producttype_name', 'Acceptance Test updated');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->expectTo('see the update');
        $I->amOnPage('/admin/list/schemas');
        $I->scrollTo($row);
        $I->see('Acceptance Test updated');

        $I->amGoingTo('delete the schema');
        $I->click('Delete', $row);
        $I->acceptPopup();
        $I->amOnPage('/admin/list/schemas');
        $I->expectTo('not see record of the attribute');
        $I->dontSeeInPageSource('Acceptance Test');
    }

    public function manageProductTypes(\Step\Acceptance\Admin $I){
        $I->loginAsAdmin();
        $I->amOnPage('/admin/list/product-types');
        $I->amGoingTo('Create an product type');
        $I->click('Add New');
        $I->fillField('#ptype_name', 'Acceptance Test');
        $I->fillField('#ptype_description', 'acceptance test');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->amOnPage('/admin/list/product-types');
        $row = Locator::contains('tr', 'Acceptance Test');
        $I->scrollTo($row);
        $I->see('Acceptance Test');

        $I->amGoingTo('update the product type');
        $I->click('Edit', $row);
        $I->fillField('#ptype_name', 'Acceptance Test updated');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->expectTo('see the update');
        $I->amOnPage('/admin/list/product-types');
        $I->scrollTo($row);
        $I->see('Acceptance Test updated');

        $I->amGoingTo('delete the attribute');
        $I->click('Delete', $row);
        $I->acceptPopup();
        $I->amOnPage('/admin/list/product-types');
        $I->expectTo('not see record of the product type');
        $I->dontSeeInPageSource('Acceptance Test');
    }

    public function manageMaterials(\Step\Acceptance\Admin $I){
        $I->loginAsAdmin();
        $I->amOnPage('/admin/list/product-materials');
        $I->amGoingTo('Create an product material');
        $I->click('Add New');
        $I->fillField('#pmaterial_name', 'Acceptance Test');
        $I->fillField('#pmaterial_description', 'acceptance test');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->amOnPage('/admin/list/product-materials');
        $row = Locator::contains('tr', 'Acceptance Test');
        $I->scrollTo($row);
        $I->see('Acceptance Test');

        $I->amGoingTo('update the product material');
        $I->click('Edit', $row);
        $I->fillField('#pmaterial_name', 'Acceptance Test updated');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->expectTo('see the update');
        $I->amOnPage('/admin/list/product-materials');
        $I->scrollTo($row);
        $I->see('Acceptance Test updated');

        $I->amGoingTo('delete the material');
        $I->click('Delete', $row);
        $I->acceptPopup();
        $I->amOnPage('/admin/list/product-materials');
        $I->expectTo('not see record of the material');
        $I->dontSeeInPageSource('Acceptance Test');
    }

    public function manageDeliveries(\Step\Acceptance\Admin $I){
        $I->loginAsAdmin();
        $I->amOnPage('/admin/list/product-deliveries');
        $I->amGoingTo('Create a product delivery');
        $I->click('Add New');
        $I->fillField('#pdelivery_name', 'Acceptance Test');
        $I->fillCkEditorById('pdelivery_description', 'acceptance test');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->amOnPage('/admin/list/product-deliveries');
        $row = Locator::contains('tr', 'Acceptance Test');
        $I->scrollTo($row);
        $I->see('Acceptance Test');

        $I->amGoingTo('update the product delivery name');
        $I->click('Edit', $row);
        $I->fillField('#pdelivery_name', 'Acceptance Test updated');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->expectTo('see the update');
        $I->amOnPage('/admin/list/product-deliveries');
        $I->scrollTo($row);
        $I->see('Acceptance Test updated');

        $I->amGoingTo('delete the product delivery');
        $I->click('Delete', $row);
        $I->acceptPopup();
        $I->amOnPage('/admin/list/product-deliveries');
        $I->expectTo('not see record of the delivery');
        $I->dontSeeInPageSource('Acceptance Test');
    }

    public function manageWarranties(\Step\Acceptance\Admin $I){
        $I->loginAsAdmin();
        $I->amOnPage('/admin/list/product-warranties');
        $I->amGoingTo('Create a product warranty');
        $I->click('Add New');
        $I->fillField('#pwarranty_name', 'Acceptance Test');
        $I->fillCkEditorById('pwarranty_description', 'acceptance test');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->amOnPage('/admin/list/product-warranties');
        $row = Locator::contains('tr', 'Acceptance Test');
        $I->scrollTo($row);
        $I->see('Acceptance Test');

        $I->amGoingTo('update the product warranty name');
        $I->click('Edit', $row);
        $I->fillField('#pwarranty_name', 'Acceptance Test updated');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->expectTo('see the update');
        $I->amOnPage('/admin/list/product-warranties');
        $I->scrollTo($row);
        $I->see('Acceptance Test updated');

        $I->amGoingTo('delete the product warranty');
        $I->click('Delete', $row);
        $I->acceptPopup();
        $I->amOnPage('/admin/list/product-warranties');
        $I->expectTo('not see record of the warranty');
        $I->dontSeeInPageSource('Acceptance Test');
    }

    public function manageProductCares(\Step\Acceptance\Admin $I){
        $I->loginAsAdmin();
        $I->amOnPage('/admin/list/product-cares');
        $I->amGoingTo('Create a product care');
        $I->click('Add New');
        $I->fillField('#pcare_name', 'Acceptance Test');
        $I->fillCkEditorById('pcare_description', 'acceptance test');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->amOnPage('/admin/list/product-cares');
        $row = Locator::contains('tr', 'Acceptance Test');
        $I->scrollTo($row);
        $I->see('Acceptance Test');

        $I->amGoingTo('update the product care name');
        $I->click('Edit', $row);
        $I->fillField('#pcare_name', 'Acceptance Test updated');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->expectTo('see the update');
        $I->amOnPage('/admin/list/product-cares');
        $I->scrollTo($row);
        $I->see('Acceptance Test updated');

        $I->amGoingTo('delete the product care');
        $I->click('Delete', $row);
        $I->acceptPopup();
        $I->amOnPage('/admin/list/product-cares');
        $I->expectTo('not see record of the product care');
        $I->dontSeeInPageSource('Acceptance Test');
    }

    public function manageProductUsage(\Step\Acceptance\Admin $I){
        $I->loginAsAdmin();
        $I->amOnPage('/admin/list/product-usage');
        $I->amGoingTo('Create a product usage');
        $I->click('Add New');
        $I->fillField('#pusage_name', 'Acceptance Test');
        $I->fillField('#pusage_description', 'acceptance test');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->amOnPage('/admin/list/product-usage');
        $row = Locator::contains('tr', 'Acceptance Test');
        $I->scrollTo($row);
        $I->see('Acceptance Test');

        $I->amGoingTo('update the product usage name');
        $I->click('Edit', $row);
        $I->fillField('#pusage_name', 'Acceptance Test updated');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->expectTo('see the update');
        $I->amOnPage('/admin/list/product-usage');
        $I->scrollTo($row);
        $I->see('Acceptance Test updated');

        $I->amGoingTo('delete the product usage');
        $I->click('Delete', $row);
        $I->acceptPopup();
        $I->amOnPage('/admin/list/product-usage');
        $I->expectTo('not see record of the product usage');
        $I->dontSeeInPageSource('Acceptance Test');
    }

    public function manageCollection(\Step\Acceptance\Admin $I){
        $I->loginAsAdmin();
        $I->amOnPage('/admin/list/prodcat');
        $I->amGoingTo('Create a collection');
        $I->click('Add New');
        $I->fillField('#id_listing_name', 'Acceptance Test');
        $I->fillField('#id_listing_seo_title', 'acceptance test');
        $I->wait(1);
        $I->see('acceptance-test', '#id_listing_url_text');
        $I->fillCkEditorById('id_listing_content1', 'test content 1');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->amOnPage('/admin/list/prodcat');
        $row = Locator::contains('tr', 'Acceptance Test');
        $I->scrollTo($row);
        $I->see('Acceptance Test');

        $I->expectTo('see a preview');
        $I->amOnPage('/draft/products/acceptance-test');
        $I->see('Acceptance Test', 'h1');
        $I->see('test content 1');

        $I->amOnPage('/admin/list/prodcat');
        $I->scrollTo($row);
        $I->amGoingTo('update the landing page');
        $I->click('Edit', $row);
        $I->fillCkEditorById('id_listing_content1', 'test content 1 edited');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->expectTo('see the update in preview');
        $I->amOnPage('/draft/products/acceptance-test');
        $I->see('test content 1 edited');

        $I->amOnPage('/admin/list/prodcat');
        $I->scrollTo($row);
        $I->amGoingTo('delete the page');
        $I->click('Delete', $row);
        $I->acceptPopup();
        $I->amOnPage('/admin/list/prodcat');
        $I->expectTo('not see record of the page anymore');
        $I->dontSeeInPageSource('Acceptance Test');
    }

    public function manageProducts(\Step\Acceptance\Admin $I){
        $I->loginAsAdmin();
        $I->amOnPage('/admin/list/products');
        $I->amGoingTo('Create a product');
        $I->click('Add New');
        $I->fillField('#id_product_name', 'Acceptance Test');
        $I->fillField('#id_product_seo_title', 'acceptance test');
        $I->wait(1);
        $I->see('acceptance-test', '#id_product_url_text');
        $I->fillCkEditorById('id_product_description', 'test content 1');
        $I->scrollTo('#Edit_Record');
        $I->click('Materials, cares & usages');
        $I->wait(1);
        $I->checkOption('#id_tbl_pmateriallink_checkbox9');
        $I->scrollTo('#Edit_Record');
        $I->wait(1);
        $I->click('Collections', '#myTab');
        $I->wait(1);
        $I->checkOption('#id_tbl_productcat_checkbox251');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->amOnPage('/admin/list/products');
        $row = Locator::contains('tr', 'Acceptance Test');
        $I->scrollTo($row);
        $I->see('Acceptance Test');

        $I->expectTo('see a preview');
        $I->amOnPage('/draft/acceptance-test');
        $I->see('Acceptance Test', 'h1');
        $I->see('test content 1');

        $I->amOnPage('/admin/list/products');
        $I->scrollTo($row);
        $I->amGoingTo('update the product');
        $I->click('Edit', $row);
        $I->fillCkEditorById('id_product_description', 'test content 1 edited');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->expectTo('see the update in preview');
        $I->amOnPage('/draft/acceptance-test');
        $I->see('test content 1 edited');

        $I->amOnPage('/admin/list/products');
        $I->scrollTo($row);
        $I->amGoingTo('delete the product');
        $I->click('Delete', $row);
        $I->acceptPopup();
        $I->amOnPage('/admin/list/products');
        $I->expectTo('not see record of the product anymore');
        $I->dontSeeInPageSource('Acceptance Test');
    }

    public function manageDiscountCodes(\Step\Acceptance\Admin $I){
        $I->loginAsAdmin();
        $I->amOnPage('/admin/list/discounts');
        $I->amGoingTo('Create a discount code');
        $I->click('Add New');
        $I->fillField('#id_discount_name', 'Acceptance Test');
        $I->fillField('#id_discount_code', 'acceptance-test');
        $I->fillField('#id_discount_amount', '10');
        $I->fillCkEditorById('id_discount_description', 'acceptance test');
        $I->checkOption('#id_discount_published');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->amOnPage('/admin/list/discounts');
        $row = Locator::contains('tr', 'Acceptance Test');
        $I->scrollTo($row);
        $I->see('Acceptance Test');

        $I->amGoingTo('update the discount');
        $I->click('Edit', $row);
        $I->fillField('#id_discount_name', 'Acceptance Test updated');
        $I->scrollTo('#Edit_Record');
        $I->click('Save');
        $I->expectTo('see the update');
        $I->amOnPage('/admin/list/discounts');
        $I->scrollTo($row);
        $I->see('Acceptance Test updated');

        $I->expect('members can use the discount code');
        $I->amOnPage( '/stainless-steel-pendant-and-curb-chain' );
        $I->click( 'label[for="colour_stainless_steel_cherry"]' );
        $I->selectOption( '#length', '74' );
        $I->selectOption( '#medical_id_size', '87' );
        $I->scrollTo('.prodaddcart');
        $I->click( 'Add to Cart' );
        $I->amOnPage('/shopping-cart');
        $I->see( '$110.50', '#total' );
        $I->amGoingTo('apply the discount code');
        $I->fillField('#promo', 'ACCEPTANCE-TEST');
        $I->click('#discount-form a.btn-red');
        $I->scrollTo('#discount-form');
        $I->wait(1);
        $I->see("'Acceptance test updated' has been successfully applied.");
        $I->see('-$10.00', '#discount');
        $I->see( '$100.50', '#total' );

        $I->amGoingTo('delete the discount');
        $I->amOnPage('/admin/list/discounts');
        $I->click('Delete', $row);
        $I->acceptPopup();
        $I->amOnPage('/admin/list/discounts');
        $I->expectTo('not see record of the discount');
        $I->dontSeeInPageSource('Acceptance Test');
    }

    public function viewOrders(\Step\Acceptance\Admin $admin, \Step\Acceptance\Member $member){
        $I=$member;
        $I->loginAsMemberWithCorrectCredentials();
        $I->amGoingTo('place an order');
        $I->amOnPage('/stainless-steel-pendant-and-curb-chain');
        $I->scrollTo('#colbox');
        $I->click( 'label[for="colour_stainless_steel_cherry"]' );
        $I->selectOption( '#length', '74' );
        $I->selectOption( '#medical_id_size', '87' );
        $I->click( 'Add to Cart' );
        $I->amOnPage('/shopping-cart');
        $I->waitForElement( '#total', 10 );
        $I->scrollTo('#total');
        $I->click('#cart-continue-btn');
        $I->waitForElement('#submit-billing', 10);
        $I->submitCheckoutBillingForm();
        $I->wait(2);
        $I->waitForElement('#payment-btn', 10);
        $I->submitCheckoutPaymentForm();
        $I->see('Your order ID is');
        $orderNumber = $I->grabTextFrom('#order-number');

        $I->expectTo('see the order as admin');
        $I=$admin;
        $I->loginAsAdmin();
        $I->amOnPage('/admin/list/orders');
        $row = Locator::contains('tr', $orderNumber);
        $I->scrollTo($row);
        $I->click('Edit', $row);
        $I->scrollTo(Locator::contains('tr', 'Item'));
        $I->see('Stainless Steel Pendant & Curb Chain');
    }

    public function manageEnqueries(\Step\Acceptance\Admin $I){
        $time = time();
        $I->amOnPage('/contact-us');
        $I->scrollTo('#contact_form');
        $I->fillField('name', ' Acceptance Tester');
        $I->fillField('email', "acceptancetester$time@them.com.au");
        $I->fillField('phone', '04511111111');
        $I->fillField('postcode', '5000');
        $I->fillField('membership_no', '1234');
        $I->selectOptionInSelectBoxItDropDown('nature_enquiry', 2);
        $I->fillField('#enquiry', 'THIS IS A TEST');
        $I->wait(2);
        $I->waitForElement('#fbsub', 10);//to pass spam prevention
        $I->click('#fbsub');
        $I->wait(1);
        $I->see('Thank you for contacting us.');

        $I->expectTo('see the enquiry in dashboard');
        $I->loginAsAdmin();
        $I->amOnPage('/admin/list/enquiries');
        $row = Locator::contains('tr', "acceptancetester$time@them.com.au");
        $I->scrollTo($row);
        $I->click('View', $row);
        $I->see('THIS IS A TEST');

        $I->expectTo('be able to delete an enquiry');
        $I->amOnPage('/admin/list/enquiries');
        $I->scrollTo($row);
        $I->click('Delete', $row);
        $I->acceptPopup();
        $I->amOnPage('/admin/list/enquiries');
        $I->expectTo('not see record of the enquiry');
        $I->dontSeeElement($row);
    }

    public function manageWishlist(\Step\Acceptance\Admin $admin, \Step\Acceptance\Member $member) {
        $I=$member;
        $I->loginAsMemberWithCorrectCredentials();
        $I->amOnPage('/products/all-products');
        $I->wait( 2 );
        $I->scrollTo('#poi-562');
        $I->click( '#poi-562 .prod-wishlist a > img' );
        $I->wait(2);

        $I=$admin;
        $I->loginAsAdmin();
        $I->amOnPage('/admin/wish-list/members');
        $member_id = getenv('MEMBER_VALID_USERNAME');
        $row = Locator::contains('tr', $member_id);
        $I->scrollTo($row);
        $I->click('View', $row);
        $I->see('Wristband Tag');

        $I->amOnPage('/products/all-products');
        $I->wait( 2 );
        $I->scrollTo('#poi-562');
        $I->click( '#poi-562 .prod-wishlist a > img' );
        $I->wait(2);

        $I->amOnPage('/admin/wish-list/members');
        $I->dontSeeElement($row);
    }
}
