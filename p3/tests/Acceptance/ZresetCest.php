<?php

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class ZResetCest
{
    // resets DB back to defaults

    public function goodWager(AcceptanceTester $I)
    {
        // set specific user and cash value
        $I->amOnPage('/');
        $goodPlayer= [
            'name' => 'Jack Black',
            'cash' => '100',
        ];
        $I->fillField('[test=name]', $goodPlayer['name']);
        $I->fillField('[test=cash]', $goodPlayer['cash']);
        $I->unCheckOption('[test=multiPlayer]');
        $I->click('[test=submit-player]');
    }
}