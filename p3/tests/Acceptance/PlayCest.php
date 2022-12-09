<?php

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class PlayCest
{
    // test play page (route: /play)

    public function _before(AcceptanceTester $I)
    {
        // set cash value to 10 - to ensure losing will end game
        $I->amOnPage('/');
        $goodPlayer= [
            'name' => 'Jack Black',
            'cash' => '10',
        ];
        $I->fillField('[test=name]', $goodPlayer['name']);
        $I->fillField('[test=cash]', $goodPlayer['cash']);
        $I->click('[test=submit-player]');
        $I->click('[test=submit-wager]');
    }

    public function finishAndLoseGame(AcceptanceTester $I)
    {
        $I->seeElement('[test=dealer-play]');
        $I->seeElement('[test=ai-play]');
        $I->seeElement('[test=player-play]');
        $I->seeElement('[test=player-hit]');
        $pointsog = $I->grabTextFrom('[test=player-score]');
        $I->comment("start points: " . $pointsog);

        $I->selectOption('[test=player-hit]', 'hit');
        $I->click('[test=hitstand-submit]');

        $I->seeElement('[test=player-score]');
        $pointsafter = $I->grabTextFrom('[test=player-score]');

        # repeat click on hit until bust
        for ($i = 0; $i < 8; $i++) {
            if ($pointsafter < 21) {
                $I->selectOption('[test=player-hit]', 'hit');
                $I->click('[test=hitstand-submit]');
                $pointsafter = $I->grabTextFrom('[test=player-score]');
            }
        }
        $I->comment("added points: " . $pointsafter);

        $I->seeElement('[test=game-over]');
    }
}