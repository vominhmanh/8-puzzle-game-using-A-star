# 8 puzzle game using A star algorithm PHP
8 – PUZZLE GAME USING A* (A-STAR) BY PHP + HTML, CSS, JS
<br>The simple way to create 8 Puzzle Game using Basic Knowledge of PHP, HTML/CSS/JS. Don't worry, you will must understand my code.
<br> You can see our demo in this link: https://rmp5.000webhostapp.com/8puzzle/

<b>Before you continue, ensure you meet the following requirements:</b>
* You have installed PHP 7.0 or later 
* You should use Chrome on PC or phone for best performance.
* You have a basic understanding of AJAX, Basic Artificial Inteligence theory.


<b>What's on my project?</b>
 * `script.js` + `index.html` + `style.css` is front-end file, creates a random state. 
 * `astar_solver.php` is server file, where solves the startState given from client.
 * `.gif` files are images

<b> How does it work? </b>
 * When website is on-load, a random state is created and sent to server by `AJAX` to find the answer path. 
 * If the state can be solved, server returns answer path. and Start state will be ready to use. As the players click `New game`, `script.js` shows this state. Moreover, another random state will be created and solved for next game.
 * If the state can not be solved in 7000 iterations, server returns `false`, `script.js` will create another state.

**State structure**
 * a state in `.js` file should look like [0, 3, 5, 6, 1, **9**, 8, 7, 2, 4], equals with:
<br>  `3 * 5 * 6 `
<br>  `1 * x * 8 `
<br>  `7 * 2 * 4 `
 * 0 to make index of array matches index of data. 

tag: a star algorithm, 8 puzzle game, sliding puzzle game, a-star algorithm, 8 puzzles, 8-puzzles, ajax, php, artificial intelligence, bfs, greedy, simple game, easy code
