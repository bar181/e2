# Project 1
+ By: Bradley Ross
+ URL: <http://e2p1.bradross.me>

## Game planning

### set game setting and default variables
+ Create an array with game options (configurable): cash available, wager for each round, number of decks to use, and when to reshuffle a deck
+ Create an array for player and dealer hit/stand logic (i.e. at what point value to hit or stand)
+ Create a deck of cards (13 cards x 4 suits x number of decks to use)
+ Create an array template to store all the activity that occurs in a round that will need to be displayed - this is used to eliminate need to set defaults for each round

### Round Activity
Repeat rounds until the player either doubles their money or goes broke
+ Shuffle the deck of cards according to the game rules (e.g. shuffle when 50% of cards have been placed)
+ Deal a single card to the player then the dealer

### Player's Turn 
Count the number of points in the player's hand
+ If the player has 21 points, the player wins the round
+ If the player has "Aces" in their hand, they can count each Ace as either 1 or 11 points (force an Ace to count as 1 point of the player has more than 21 points)
+ If the player has more than 21 points, the player loses the round
+ If the player has less than then 21 points, the player will use pre-determined logic to decide if they want an additional card "hit" and repeat the player's turn process
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

### Determine Winner
+ If the round winner was not already decided during the player or dealer's turn. the player or dealer with the highest card values wins
+ If the player and dealer have the same point value, the round is a tie


### End of Round Details
+ Update the player and dealer cash values depending on who wins (ties mean the wager is returned)
+ Save the round details in an array (e.g. cards, point values, winner, and ending cash balance)
+ If the player or dealer has $0 cash remaining the game is over

### Display Output
+ Show the number of rounds played and the overall winner
+ Show the rounds details in a table


## Outside resources
[Generic blackjack rules for game play, logic and rules](https://en.wikipedia.org/wiki/Blackjack)
[Suits icons css](https://hesweb.dev/files/e2p1-examples/war/)
[Table css](https://www.w3schools.com/css/tryit.asp?filename=trycss_table_fancy)
[Color theme for table colors](https://material.io/design/color/the-color-system.html#color-theme-creation)


## Notes for instructor
Have fun !

# commit logs
sep 18 - add require - for week 3 assignment