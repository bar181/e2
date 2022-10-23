# Project 2
+ By: Bradley Ross
+ URL: <http://e2p2.bradross.me>

## Game Planning
General
+ User completes a form based on their process
+ Forms are posted to process.php: save inputs to sessions, identifies the new process page
+ Index runs logic depending on the process saved in sessions
+ Index-view displays html and views/forms as determined by the index logic

Set up: 
+ View: Shows welcome page
+ Form: The user enters their name and cash available.  Multiplayer options that will show an additional player (AI not user input)
+ Process: Saves setup details in sessions
+ Logic: Sets defaults for other session variables

New Round:
+ View: Displays lifetime stats for the user (wins, ties, loses, blackjacks)
+ Form: Wager for the round or show end of game (if user is out of money)
+ Process: Sets sessions to default (null) round
+ Logic: Starts the game play - shuffle a deck of cards, deals a card to the dealer, user and AI player

Round - User Hit or Stand:
+ View: Displays cards and point values for the dealer, user and AI player
+ Form: User hits (request another card) or stands (ends round)
+ Process: updates Hit or Stand session, repeat this stage until user busts or stands
+ Logic: If user hits, add a new card from the deck and update total.  If user busts or stands, the round is over

End of Round:
+ View: Show round results (win, lose or tie), update cash balance
+ Form: Continue to new round page 
+ Process: Reset round and cards to make ready for a new round
+ Logic: Dealer and AI player game play based on pre-determined logic, identify round winners, update player totals

End of Game:
+ Display message if user is out of money

Quit Button (display in navbar or end of game):
+ Resets to the start of the game allowing the user to add more cash


## Files and File Purpose

```
p2
├── dogs_poker.jpg          image of a painting by Brad Ross (acrylic on canvas)
└── index.php               logic page
└── process.php             landing page for posts to update sessions (redirects back to index when done)
└── index.view.php          main layout page with html, links to bootstrap, css and views
└── instructions.view.php   instructions to user 
└── navbar.view.php         navigation bar with title, cash balance, and quit button 
└── newround.view.php       form to process wager for a new round, shows user lifetime stats 
└── play.view.php           form for main game play (user hit or stand), and displays details for each round  
└── setup.view.php          form at the start of game (user name and cash available) 
└── styles.css              custom styles
```

## Outside resources (new)
+ [Bootstrap 5.2 CSS only](https://getbootstrap.com/docs/5.2/getting-started/introduction/)
+ [Bootstrap 5 Forms](https://getbootstrap.com/docs/5.0/forms/overview/)
+ [Cards in Unicode](https://en.wikipedia.org/wiki/Playing_cards_in_Unicode)
+ [E2 Issues log for ideas](https://github.com/susanBuck/e2-fall22/issues)
+ [PHP Sessions support](https://www.w3schools.com/php/php_sessions.asp)
+ [Markdown Tree format](https://www.w3schools.io/file/markdown-folder-tree/)
+ [Wiki Commons image - NOT USED](https://commons.wikimedia.org/wiki/File:13-02-27-spielbank-wiesbaden-by-RalfR-051.jpg)


## Outside resources (used in Project 1)
+ [Generic blackjack rules for game play, logic and rules](https://en.wikipedia.org/wiki/Blackjack)
+ [Suits icons css from course example](https://hesweb.dev/files/e2p1-examples/war/)
+ [Table css](https://www.w3schools.com/css/tryit.asp?filename=trycss_table_fancy)
+ [Color theme for table colors](https://material.io/design/color/the-color-system.html#color-theme-creation)
+ [refresher on associative arrays](https://www.w3schools.com/php/php_arrays_associative.asp)


## Notes for instructor
+ Enjoy !

## Change Log
+ [Refactor of original game with functions](https://github.com/bar181/e2/commit/2a3e87a52b510cb453a26a61bdd6699c9ce2691b)
+ [E2P2 layout and setup - 1st Draft showing layout](https://github.com/bar181/e2/commit/8360c96ed4abb5614d8b06ccd0902e40f289b8c3)
+ [E2P2 working version](https://github.com/bar181/e2/commit/0e9fbf19afd248a9cfc3f4550ba6f7b6f1a53ad6)
+ [e2p2 Add multiplayer - adds a computer generated player option](https://github.com/bar181/e2/commit/4aab400588ffe03ef5defb886b8128930e5df20c)
+ [e2p2 multiple views - change design to include multiple views](https://github.com/bar181/e2/commit/8e4c246234466b1f6d61c1fe17e6c54f65323cf0)


