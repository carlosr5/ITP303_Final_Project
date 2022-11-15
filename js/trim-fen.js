export function trimFen(fen) {
    let newFen;

    // Trim fen
    for (let i = 0; i < fen.length; i++) {
        if (fen[i] == ' ') {
            newFen = fen.substr(0, i);
            break;
        }
    }

    return newFen;
}