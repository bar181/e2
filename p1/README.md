# Project 1
+ By: Bradley Ross
+ URL: <http://e2p1.bradross.me>

## Game planning

### game settings and default variables
+ Create an array with house rule game options (configurable): cash available, wager for each round, number of decks to use, and when to reshuffle a deck. 
+ Set the shoe size variable based on the game options (uses a formula)
+ Create an array for player and dealer hit/stand logic (i.e. at what point value to hit or stand). The logic for the player is based on the quality of the dealer's hand at the start of each round
+ Create an array that holds the display and point value of each card
+ Create a master deck of all cards (13 cards x 4 suits x number of decks to use) and an empty array that will contain the deck of cards used in the game
+ Create a variable to keep track of the player and dealer cash balance
+ Create an array template to store all the activity that occurs in a round that will need to be displayed - this is used to eliminate need to set defaults for each round


### Round Activity
Repeat rounds until the player either doubles their money or goes broke
+ Shuffle the deck of cards according to the game rules (e.g. shuffle when 50% of cards have been placed)
+ Start tracking the round details in an array
+ Deal a single card to the player then the dealer

### Player's Turn 
The player's turn continues until they "stand" or have a point total of 2 or more
+ Count the number of points in the player's hand
+ If the player has 21 points, the player wins the round
+ If the player has "Aces" in their hand, they can count each Ace as either 1 or 11 points 
+ If the player has more than 21 points, the player loses the round
+ If the player has less than then 21 points, the player will use pre-determined logic to decide if they want an additional card "hit" 
+ If the player hits, they receive a new card from the deck and the player's turn continues
+ If the player "stands", their turn ends and it is the dealer's turn 
+ track player activity 

### Dealer's Turn 
Count the number of points in the dealer's hand
+ If the dealer has "Aces" in their hand, they can count each Ace as either 1 or 11 points
+ The dealer must hit if their total point value is under 16 (or other value as determined in the game logic / house rules)
+ If the dealer has more than 21 points, the dealer loses the round
+ If the dealer hits, they receive a new card from the deck and their urn continues
+ track dealer's activity 

### Winner of the Round
+ If the round winner was not already decided during the player or dealer's turn, the highest total card values wins
+ If the player and dealer have the same point value, the round is a tie

### End of Round Details
+ Update the player and dealer cash values depending on who wins (ties mean the wager is returned)
+ Save the round details in an array (e.g. cards, point values, winner, and ending cash balance)
+ Repeat the round activity process until either player or dealer has $0 cash remaining 

### End of Game Details
+ Identify the total number of rounds played
+ Identify the game winner 

### Display Output - HTML page
+ External CSS file - use minimal CSS as per specs
+ Show the number of rounds played and the overall winner
+ Table with details showing tracked activity for each round (show all cards for both player and dealer for each round) 

## Specifications Review
+ This is a two player game where one player is called "Player" and the other is "Dealer"
+ Random choices used when shuffling deck
+ Pre-programmed strategies included for player hit/stand options (configurable)
+ No user inputs required
+ Game follows standard "Blackjack" casino rules 
+ Game resides on public site using its own document root. Repository is public on GitHub

## Outside resources
+ [Generic blackjack rules for game play, logic and rules](https://en.wikipedia.org/wiki/Blackjack)
+ [Suits icons css from course example](https://hesweb.dev/files/e2p1-examples/war/)
+ [Table css](https://www.w3schools.com/css/tryit.asp?filename=trycss_table_fancy)
+ [Color theme for table colors](https://material.io/design/color/the-color-system.html#color-theme-creation)
+ [refresher on associative arrays](https://www.w3schools.com/php/php_arrays_associative.asp)


## Notes for instructor
None. Have fun ! 


## Commit logs
+ [Sep 24/22: Blackjack game logic - initial](https://github.com/bar181/e2/commit/30a5c5257c9fae788113446f540426e86a95e3f3)
+ [Sep 30/22: e2p1 run tests](https://github.com/bar181/e2/commit/c9a302e4c3f649d085b1c4a9844ae932d8908e68)
+ [Oct 2/22: add round output with css](https://github.com/bar181/e2/commit/4e4cde8f049a334bfba99ddc312b6d6075b6dfae)
+ [Oct 3/22: refactoring and readme](https://github.com/bar181/e2/commit/f2cda83316f4ea26735ef93c05370fe4f710efd6)
+ [Oct 3/22: e2p1 ready for final review](https://github.com/bar181/e2/commit/8fb83a454441da62daf3165db55a5dfa7a827526)
+ Final commit made on Oct 4/22 for submission