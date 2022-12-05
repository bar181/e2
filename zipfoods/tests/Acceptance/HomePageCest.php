<?php

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class HomePageCest
{
    // tests
    public function homeOpens(AcceptanceTester $I)
    {
        # Act
        $I->amOnPage('/');

        # Assert the correct title is set on the page
        $I->see('Welcome', '[test=welcome]');
    }
}