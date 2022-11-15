// Will play a random opening from the database. For now, will code the logic s.t. it plays the Caro Kann opening 4 move deep
// Initial config
let demoConfig = {
    position: 'start',
    moveSpeed: 'slow'
};

let demoBoard = Chessboard('demo-board', demoConfig);

// Returns a Promise that resolves after "ms" Milliseconds
// Src: https://stackoverflow.com/questions/3583724/how-do-i-add-a-delay-in-a-javascript-loop
const timer = ms => new Promise(res => setTimeout(res, ms));

// Opening function that displays the animation for the Caro Kann.
// First few opening moves for the Caro Kann Classical Variation
let caroKann = [
    'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3 0 1',
    'rnbqkbnr/pp1ppppp/2p5/8/4P3/8/PPPP1PPP/RNBQKBNR w KQkq - 0 2',
    'rnbqkbnr/pp1ppppp/2p5/8/3PP3/8/PPP2PPP/RNBQKBNR b KQkq d3 0 2',
    'rnbqkbnr/pp2pppp/2p5/3p4/3PP3/8/PPP2PPP/RNBQKBNR w KQkq d6 0 3',
    'rnbqkbnr/pp2pppp/2p5/3p4/3PP3/2N5/PPP2PPP/R1BQKBNR b KQkq - 1 3',
    'rnbqkbnr/pp2pppp/2p5/8/3Pp3/2N5/PPP2PPP/R1BQKBNR w KQkq - 0 4',
    'rnbqkbnr/pp2pppp/2p5/8/3PN3/8/PPP2PPP/R1BQKBNR b KQkq - 0 4',
    'rn1qkbnr/pp2pppp/2p5/5b2/3PN3/8/PPP2PPP/R1BQKBNR w KQkq - 1 5'
];

async function displayOpening(board, opening) {
    // Initializes the display board
    demoBoard.position('start');

    for (let j = 0; j < opening.length; j++) {
        // Updates the position every second
        let pos = opening[j];

        await timer(1000);

        board.position(pos);
    }
}

// Handing the resizing of the board with this library function
$(window).on('resize', function () {
    demoBoard.resize();
});

displayOpening(demoBoard, caroKann);