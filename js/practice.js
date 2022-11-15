// If signed in, will allow the player to practice openings that are in their opening rep. 
// Else, will allow player to practice popular openings

import { Chess } from "../node_modules/chess.js/chess.js";
import { trimFen } from "./trim-fen.js";

/** VARIABLE DECLARATIONS */

// Variables for the visual board and logic
let practiceBoard = null;
let game = new Chess();

// This is the current FEN of the board. Just a nice variable to have in case we want it for future use.
let currFen = 'start';
let currMoveNumber = 0;
let currTurn = 'user';
let currColor = 'w';

// Elements on page to update with the current status and pgn
let $status = $('#status');
let $pgn = $('#pgn');
let $opening_name = $('#opening-name');

const timer = ms => new Promise(res => setTimeout(res, ms));

// Defining the Caro Kann for demonstration purposes
let openingMoves = [
    'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR',
    'rnbqkbnr/pp1ppppp/2p5/8/4P3/8/PPPP1PPP/RNBQKBNR',
    'rnbqkbnr/pp1ppppp/2p5/8/3PP3/8/PPP2PPP/RNBQKBNR',
    'rnbqkbnr/pp2pppp/2p5/3p4/3PP3/8/PPP2PPP/RNBQKBNR',
    'rnbqkbnr/pp2pppp/2p5/3p4/3PP3/2N5/PPP2PPP/R1BQKBNR',
    'rnbqkbnr/pp2pppp/2p5/8/3Pp3/2N5/PPP2PPP/R1BQKBNR',
    'rnbqkbnr/pp2pppp/2p5/8/3PN3/8/PPP2PPP/R1BQKBNR',
    'rn1qkbnr/pp2pppp/2p5/5b2/3PN3/8/PPP2PPP/R1BQKBNR'
];

let practiceConfig = {
    position: currFen,
    // moveSpeed: 'slow',
    draggable: true,
    onDragStart: onDragStart,
    onDrop: onDrop
};

/** FUNCTION DECLARATIONS */

// AJAX HANDLING BACK-END INFORMATION

// Doing an AJAX get request
function ajaxGet(endpointUrl, returnFunction) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', endpointUrl, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == XMLHttpRequest.DONE) {
            if (xhr.status == 200) {
                returnFunction(xhr.responseText);
            } else {
                alert('AJAX Error!');
                console.log(xhr.status);
            }
        }
    }
    xhr.send();
};

// Function called when we start dragging a piece
function onDragStart(source, piece, position, orientation) {
    // do not pick up pieces if the game is over
    if (game.game_over()) return false;
}

// Function called when we drop a piece. We use this one to make sure we're dropping the piece to the right location
function onDrop(source, target, piece, newPos, oldPos, orientation) {

    // Checking if fen of each move matches.
    // First we store the fen of the new position
    let tempFen = Chessboard.objToFen(newPos);

    // We check if it's the the correct opening. If not, then snap back the piece
    if (openingMoves[currMoveNumber] != tempFen) {
        return 'snapback';
    }

    // Updating background logic
    game.move({
        from: source,
        to: target
    });

    currMoveNumber++;
    updateStatus();
}

function getRandOpening(openingMovesJSON) {
    // Should do this with SQL, but I'm gonna count the number of openings here. I'm assuming that the db si sorted, so we can simply find out the last item's move id
    let numOpenings = openingMovesJSON[openingMovesJSON.length - 1].move_id;

    // SEPARATE FUNCTION: Get a random opening
    // Getting a number bt 1 & 2, multiplying it by numOpenings so we get bt 0 & numOpenings, then takkng the floor
    let randOpeningNum = Math.floor(numOpenings * Math.random() + 1);

    // Adjusting base case
    if (randOpeningNum == numOpenings + 1) randOpeningNum--;

    // Storing our rand opening
    openingMoves = [];

    // Doing linear search to look through our array. There's an algo where you can do binary search to find the endpoints of the subarray with our desired opening moves. Did it on LC, but it's long
    let openingName = '';
    openingMovesJSON.forEach(opening_move => {
        if (opening_move.move_id == randOpeningNum) {
            let adjustedFen = trimFen(opening_move.fen_str);

            openingMoves.push(adjustedFen);

            openingName = opening_move.name;
        }
    });

    resetBoard(openingName);
}

function resetBoard(move_name) {
    // Adjusting chess.js logic
    // Restart any game logic that was there
    game.reset();

    currMoveNumber = 0;

    // Adjusting chessboard.js display
    // Double check
    practiceBoard.start;
    $opening_name.html(move_name);
}

async function updateStatus() {

    // Is it the computer's turn?
    if (currTurn == 'comp') {
        await timer(1000);

        // Update the visual board
        practiceBoard.position(openingMoves[currMoveNumber++]);

        // Handling board logic / pgn
        // Explore the possible moves and see which FEN str matches the one that we want
        // Can trim by deleting everything else after the first whitespace
        let moves = game.moves();

        // O(m*l) algo where m = length of move set and l = length of fen string. Not optimal but it works for now
        moves.every(move => {
            // First, apply the move to the game
            game.move(move);

            // Then, get the game fen
            // let logicFen = game.fen();
            let logicFen = trimFen(game.fen());

            // Check if it matches the practiceBoard fen and break if found
            if (logicFen == practiceBoard.fen()) {
                return false;
            }

            // Otherwise, undo the move
            game.undo();
            return true;
        });
    }

    // Updating the on-screen messages
    $status.html("Current move: " + (Math.floor(currMoveNumber / 2) + 1));
    $pgn.html(game.pgn());

    // If at least one move has been made, we enter this conditional statement. If we don't have this condition, then the first move is automatically made. This wrapper is needed.
    if (currMoveNumber > 0) {
        // Now, it's the computer's turn and they make a move
        if (currTurn == 'user' && currColor == 'w') {
            currTurn = 'comp';
            currColor = 'b';
            updateStatus();
        }
        else if (currTurn == 'user' && currColor == 'b') {
            currTurn = 'comp';
            currColor = 'w';
            updateStatus();
        }
        // Else, it's the user's turn and they make a move
        else if (currTurn == 'comp' && currColor == 'w') {
            currTurn = 'user';
            currColor = 'b';
        }
        else if (currTurn == 'comp' && currColor == 'b') {
            currTurn = 'user';
            currColor = 'w';
        }
    }
}
/** MAIN CODE */

// CHESSBOARD CODE - Displaying and updating the chessboard correctly
// Displaying the chessboard based on our initial config
practiceBoard = Chessboard('practice-board', practiceConfig);

// Handling the resizing of the board with the library function
$(window).on('resize', function () {
    practiceBoard.resize();
});

updateStatus();

// OPENINGS CODE - Getting random openings
ajaxGet("getting-openings.php", function (response) {
    console.log("Opening moves JSON");
    let openingMovesJSON = JSON.parse(response);
    console.log(openingMovesJSON);

    getRandOpening(openingMovesJSON);
});

// Reset Button
$("#reset-btn").on("click", function (event) {
    let $currOpeningName = $("opening-name").html();
    resetBoard($currOpeningName);

    $pgn.html('');

});

$("#reset-btn").on("click", practiceBoard.start);
// Also have a new opening be loaded in every time someone successfully completes an opening. For now, just display an alert saying that they got it right