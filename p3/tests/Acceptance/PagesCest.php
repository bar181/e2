<?php

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class PagesCest
{
    // test not standard pages
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

    public function gameStatsDivShows(AcceptanceTester $I)
    {
        $I->amOnPage('/history');
        $I->seeElement('[test=game-stats]');
    }

    public function error404(AcceptanceTester $I)
    {
        $I->amOnPage('/i-am-a-404');
        $I->seeElement('[test=page-404]');
    }
}