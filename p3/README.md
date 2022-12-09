# Project 3
+ By: Bradley Ross
+ URL: <http://e2p3.bradross.me>


## Graduate requirement
+ [x] I have integrated testing into my application
+ [ ] I am taking this course for undergraduate credit and have opted out of integrating testing into my application

## Requirements Summary
+ 3 URL pages: 8 pages complete.  5 direct URL pages (/, wager, play, history, round) plus 3 processing pages
+ Routes: 9 routes complete.
+ Controllers: 4 complete.  Includes: Controller, App (process URL pages), Helper (static functions), Post (process forms)
+ Views: 14 custom blade templates plus amended 404 and template pages. 
+ Database - 1+ table.  3 complete.  players (player and progress data), rounds (round summary, win/tie/loss), hands (details for the specific hand being played for each player)
+ Database - Create.  Results saved each round. Player configuration saved each new game.  Hands saved each time player selects to either hit or stand.
+ Database - Read.  Complete. Used on all pages. 
+ Database migration and seeds. Complete.  In terminal: "php console App fresh" 
+ Must use the $app->db() method for all database interactions.  Complete.  This includes: all, findById, insert, createTable (in migrations), run (custom update method uses run)
+ Form validation. Complete for 4 form fields. See: PostController/post_setup, PostController/post_wager.  Radio buttons use both front end "checked" and backend default values.
+ Testing for graduate students: 9 tests, 19 assertions.  See below.


## Outside resources

+ [timestamp format with php ](https://stackoverflow.com/questions/5632662/saving-timestamp-in-mysql-table-using-php)
+ [playing cards css  ](https://en.wikipedia.org/wiki/Playing_cards_in_Unicode)
+ [display html entities in blade template](https://stackoverflow.com/questions/29253979/displaying-html-with-blade-shows-the-html-code)
+ [cannot  access $this in a static function ](https://stackoverflow.com/questions/2286696/)
+ [UPDATE query using PDO  ](https://phpdelusions.net/pdo_examples/update)



## Notes for instructor
+ This game uses database updates allowing players to return to the same point in the game.  There is a button "continue" in the navigation bar if you take a break.  Personal preference to use databases instead of sessions 



## Design Summary
+ Blackjack game using frameworks and saving results to a database

### Set up (route /)
+ User resets the game with their name, starting cash balance and option to play with a computer player

### Form handling for setup (route /post_setup)
+ Form fields are validated with error messages if the user enters an invalid name or cash balance
+ Database is updated with new player details

### Wager (route /wager)
+ User selects a $10 or $50 wager. The $50 wage will only appear if the user has adequate funds

### Form handling for Wager (route /post_wager)
+ wager amount is validated 
+ Initiates default settings for a new round - database inserts to rounds and hands tables

### Play (route /play)
+ retrieves round, player and hand data from database to determine what to appear on the front end
+ New hand process: Shuffles a new deck of cards and deals cards.  Updates database with initial cards and parameters
+ Uses helper functions to calculate a player's point value, display cards
+ View includes blade templates for each section.  If statement are use to show/hide blades (e.g. the round over blade will appear once the round is over)
+ During game play (before end of round) the user can select to either stand or hit for more cards
+ The player repeats wager/play process until they run out of funds
+ End of game blade appears when use player is out of funds

### Form handling for play (route /post_play)
+ retrieves database information
+ If the user decides to hit: a new card is dealt to the player with point values updated and saved to the database.
+ The round ends when the user either stands or the player's point value is 21 or more after hit

### End of round processing (route /post_play where endRound is true)
+ the computer player completes their game (there are 3 levels of hit/stand options)
+ the dealer completes their game
+ round outcomes are determined (win/tie/loss) with the player's cash value updated
+ database tables are updated with results

### History (route /history)
+ Shows all rounds with option to view details for any round

### Round Details (route / round)
+ Shows details for a specific rounds


### Codeception testing output

```
Codeception PHP Testing Framework v5.0.5 https://helpukrainewin.org

Tests.Acceptance Tests (9) ------------------------------------------------------------------------------------------------------------------
PagesCest: History and round page
Signature: Tests\Acceptance\PagesCest:historyAndRoundPage
Test: tests/Acceptance/PagesCest.php:historyAndRoundPage
Scenario --
 I am on page "/history"
 I grab multiple "[test=round-link]"
 history roundCount 30
 I assert greater than or equal 10,30
 I grab text from "[test=round-link]"
 I click "Round: 30 on 2022-12-09 15:32:37"
 I see "2022-12-09 15:32:37"
 PASSED 

PagesCest: Game stats div shows
Signature: Tests\Acceptance\PagesCest:gameStatsDivShows
Test: tests/Acceptance/PagesCest.php:gameStatsDivShows
Scenario --
 I am on page "/history"
 I see element "[test=game-stats]"
 PASSED 

PagesCest: Error404
Signature: Tests\Acceptance\PagesCest:error404
Test: tests/Acceptance/PagesCest.php:error404
Scenario --
 I am on page "/i-am-a-404"
 I see element "[test=page-404]"
 PASSED 

PlayCest: Finish and lose game
Signature: Tests\Acceptance\PlayCest:finishAndLoseGame
Test: tests/Acceptance/PlayCest.php:finishAndLoseGame
Scenario --
 I am on page "/"
 I fill field "[test=name]","Jack Black"
 I fill field "[test=cash]","10"
 I click "[test=submit-player]"
 I click "[test=submit-wager]"
 I see element "[test=dealer-play]"
 I see element "[test=ai-play]"
 I see element "[test=player-play]"
 I see element "[test=player-hit]"
 I grab text from "[test=player-score]"
 start points: 21
 I select option "[test=player-hit]","hit"
 I click "[test=hitstand-submit]"
 I see element "[test=player-score]"
 I grab text from "[test=player-score]"
 I select option "[test=player-hit]","hit"
 I click "[test=hitstand-submit]"
 I grab text from "[test=player-score]"
 added points: 30
 I see element "[test=game-over]"
 PASSED 

SetupCest: Good new user
Signature: Tests\Acceptance\SetupCest:goodNewUser
Test: tests/Acceptance/SetupCest.php:goodNewUser
Scenario --
 I am on page "/"
 I fill field "[test=name]","Test Name"
 I fill field "[test=cash]","100"
 I uncheck option "[test=multiPlayer]"
 I click "[test=submit-player]"
 I see element "[test=wager-page]"
 I don't see element "[test=navbar-multiplayer]"
 PASSED 

SetupCest: New multiplayer user
Signature: Tests\Acceptance\SetupCest:newMultiplayerUser
Test: tests/Acceptance/SetupCest.php:newMultiplayerUser
Scenario --
 I am on page "/"
 I fill field "[test=name]","Test Name"
 I fill field "[test=cash]","100"
 I check option "[test=multiPlayer]"
 I click "[test=submit-player]"
 I see element "[test=navbar-multiplayer]"
 PASSED 

SetupCest: Bad new user
Signature: Tests\Acceptance\SetupCest:badNewUser
Test: tests/Acceptance/SetupCest.php:badNewUser
Scenario --
 I am on page "/"
 Test to ensure name error
 I fill field "[test=name]","A"
 I fill field "[test=cash]","100"
 I uncheck option "[test=multiPlayer]"
 I click "[test=submit-player]"
 I see element "[test=product-added-error]"
 Test cash too low error
 I fill field "[test=name]","Test Name"
 I fill field "[test=cash]","1"
 I click "[test=submit-player]"
 I see element "[test=product-added-error]"
 Test cash too high error
 I fill field "[test=name]","Test Name"
 I fill field "[test=cash]","1000000"
 I click "[test=submit-player]"
 I see element "[test=product-added-error]"
 PASSED 

WagerCest: Good wager
Signature: Tests\Acceptance\WagerCest:goodWager
Test: tests/Acceptance/WagerCest.php:goodWager
Scenario --
 I am on page "/"
 I fill field "[test=name]","Wager Player"
 I fill field "[test=cash]","40"
 I click "[test=submit-player]"
 I am on page "/wager"
 I see "40","[test=navbar-cash]"
 I don't see element "[test=wager-w50]"
 I click "[test=submit-wager]"
 PASSED 

ZResetCest: Reset player defaults
Signature: Tests\Acceptance\ZResetCest:resetPlayerDefaults
Test: tests/Acceptance/ZresetCest.php:resetPlayerDefaults
Scenario --
 I am on page "/"
 I fill field "[test=name]","Jack Black"
 I fill field "[test=cash]","100"
 I uncheck option "[test=multiPlayer]"
 I click "[test=submit-player]"
 PASSED 

---------------------------------------------------------------------------------------------------------------------------------------------
Time: 00:00.931, Memory: 10.00 MB

OK (9 tests, 19 assertions)

```