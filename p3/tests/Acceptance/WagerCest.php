<?php

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class WagerCest
{
    // test wager page (route: /wager)

    public function _before(AcceptanceTester $I)
    {
        // set specific user and cash value
        $I->amOnPage('/');
        $goodPlayer= [
            'name' => 'Wager Player',
            'cash' => '40',
        ];
        $I->fillField('[test=name]', $goodPlayer['name']);
        $I->fillField('[test=cash]', $goodPlayer['cash']);
        $I->click('[test=submit-player]');
    }

    public function goodWager(AcceptanceTester $I)
    {
        $I->amOnPage('/wager');
        $I->see('40', '[test=navbar-cash]');
        $I->dontSeeElement('[test=wager-w50]');
        $I->fillField('[test=wager-w10]', '10');
        $I->click('[test=submit-wager]');

        // $I->see('90', '[test=navbar-cash]');
    }
}
