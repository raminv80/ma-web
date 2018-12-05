<?php
namespace Step\Acceptance;
use Page\AdminLogin as AdminLogin;

class Admin extends \AcceptanceTester
{

    public function loginAsAdmin()
    {
        $I=$this;
        $I->amGoingTo('login as admin');
        $I->amOnPage(AdminLogin::$URL);
        $I->fillField(AdminLogin::$usernameField, 'online@them.com.au');
        $I->fillField(AdminLogin::$passwordField, '^L106T28uSuc');
        $I->click(AdminLogin::$loginButton);
        $I->wait(1);
        $I->see('Stay up to date');
    }

    public function fillCkEditorById($element_id, $content) {
        $this->scrollTo('#cke_' . $element_id);
        $this->fillRteEditor(
            \Facebook\WebDriver\WebDriverBy::cssSelector(
                '#cke_' . $element_id . ' .cke_wysiwyg_frame'
            ),
            $content
        );
    }

    private function fillRteEditor($selector, $content) {
        $this->executeInSelenium(
            function (\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver)
            use ($selector, $content) {
                $webDriver->switchTo()->frame(
                    $webDriver->findElement($selector)
                );

                $webDriver->executeScript(
                    'arguments[0].innerHTML = "' . addslashes($content) . '"',
                    [$webDriver->findElement(\Facebook\WebDriver\WebDriverBy::tagName('body'))]
                );

                $webDriver->switchTo()->defaultContent();
            });
    }
}
