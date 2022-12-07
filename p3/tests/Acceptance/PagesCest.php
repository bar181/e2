<?php

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class PagesCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // test new user set up page (route: /history)
    public function historyAndRoundPage(AcceptanceTester $I)
    {
        $I->amOnPage('/history');
        $roundCount= count($I->grabMultiple('[test=round-link]'));
        $I->comment("history roundCount " .  $roundCount);
        $I->assertGreaterThanOrEqual(10, $roundCount);

        $buttonContent = $I->grabTextFrom('[test=round-link]');
        $I->click($buttonContent);
        $I->see(substr($buttonContent, -19));      // check timestamp (last 19 chars)
    }
}