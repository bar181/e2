<?php

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class SetupCest
{
    // test new user set up page (route: /)
    public function goodNewUser(AcceptanceTester $I)
    {
        $I->amOnPage('/');

        $goodPlayer= [
            'name' => 'Test Name',
            'cash' => '100',
        ];

        $I->fillField('[test=name]', $goodPlayer['name']);
        $I->fillField('[test=cash]', $goodPlayer['cash']);
        $I->unCheckOption('[test=multiPlayer]');

        $I->click('[test=submit-player]');
        $I->seeElement('[test=wager-page]');
        $I->dontSeeElement('[test=navbar-multiplayer]');
    }

    public function newMultiplayerUser(AcceptanceTester $I)
    {
        $I->amOnPage('/');

        $goodPlayer= [
            'name' => 'Test Name',
            'cash' => '100',
        ];

        $I->fillField('[test=name]', $goodPlayer['name']);
        $I->fillField('[test=cash]', $goodPlayer['cash']);
        $I->checkOption('[test=multiPlayer]');

        $I->click('[test=submit-player]');
        $I->seeElement('[test=navbar-multiplayer]');
    }

     public function badNewUser(AcceptanceTester $I)
     {
         $I->amOnPage('/');

         $goodPlayer= [
             'name' => 'Test Name',
             'cash' => '100',
         ];

         # bad name
         $I->comment("Test to ensure name error");
         $I->fillField('[test=name]', "A");
         $I->fillField('[test=cash]', $goodPlayer['cash']);
         $I->unCheckOption('[test=multiPlayer]');

         $I->click('[test=submit-player]');
         $I->seeElement('[test=product-added-error]');

         # cash too low
         $I->comment("Test cash too low error");
         $I->fillField('[test=name]', $goodPlayer['name']);
         $I->fillField('[test=cash]', '1');
         $I->click('[test=submit-player]');
         $I->seeElement('[test=product-added-error]');

         # cash too high
         $I->comment("Test cash too high error");
         $I->fillField('[test=name]', $goodPlayer['name']);
         $I->fillField('[test=cash]', '1000000');
         $I->click('[test=submit-player]');
         $I->seeElement('[test=product-added-error]');
     }
}