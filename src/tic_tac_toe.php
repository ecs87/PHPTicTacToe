<?php
/*
 * Please fix each todo in this file in order.
 * Starting from the top.
 * When you are finished and run it, the tests should pass and you should be able to play.
 *
 * If you find other bugs, fix them as well.
 */




/**
 * Keeps track of pieces
 */
class Board
{
    const ROWS = 3;
    const COLUMNS = 3;

    /** @var string[][] */
    private $pieces;

    /**
     * @param string[][] $pieces
     */
    public function __construct($pieces = [])
    {
        $this->pieces = $this->fillBlanks($pieces);
    }

    /**
     * A visual representation of the game board
     * @return string
     */
    function __toString()
    {
        $string = '';
		$i = 0;
        foreach ($this->pieces as $column) {

            foreach ($column as $piece) {
            		if ($piece === '') {
                    $string .= ('-');
                    $i++;
                } else {
                    $string .= $piece;
                    $i++;
                }
            }
						$string .= ("\n");
        }

        return $string;
    }

    /**
     * @return bool True when there are no more empty spaces
     */
    public function isFull()
    {
        foreach ($this->pieces as $columns) {
            foreach ($columns as $piece) {
                if ($piece === '') {
                    return false;
                }
            }
        }

        return true;
    }


    /**
     * @return array Array of all possible positions [x,y]...
     */
    private function getAllPositions()
    {
        $positions = [];

        for ($y = 0; $y < self::ROWS; $y++) {
            for ($x = 0; $x < self::COLUMNS; $x++) {
                $positions[] = [$y, $x];
            }
        }

        return $positions;
    }

    /**
     * Define each possible row/column combination with an empty string ''
     * @param string[][] $pieces
     * @return string[][]
     */
    private function fillBlanks(array $pieces)
    {
        foreach ($this->getAllPositions() as list($y, $x)) {

            if (!isset($pieces[$y][$x])) {
                $pieces[$y][$x] = '';
            }
        }

        return $pieces;
    }

    /**
     * Todo
     * Return a list of positions (an array of [y, x] pairs) that have no piece (where the value is '')
     * @return int[][] A list of blank positions, [x, y]...
     */
    public function getBlankPositions($board)
    {
		$blanks = array();
		for ($j = 0; $j < 3; $j++) {
			for ($i = 0; $i < 3; $i++) {
				if ($board->getPieceAtPosition($j,$i) == '') {
					array_push($blanks, array($j, $i));
				}
			}
		}
        return $blanks;
    }

    /**
     * @param int $y Row
     * @param int $x Column
     * @param string $piece Piece, 'O' or 'X'
     * @return Board
     */
    public function addPiece($y, $x, $piece)
    {
        $this->pieces[$y][$x] = $piece;

        return $this;
    }

    public function getWinner($board, $movePosition)
    {
		if ($board->getPieceAtPosition(0,0) == Player::PIECE && $board->getPieceAtPosition(0,1) == Player::PIECE && $board->getPieceAtPosition(0,2) == Player::PIECE
            || $board->getPieceAtPosition(1,0) == Player::PIECE && $board->getPieceAtPosition(1,1) == Player::PIECE && $board->getPieceAtPosition(1,2) == Player::PIECE
            || $board->getPieceAtPosition(2,0) == Player::PIECE && $board->getPieceAtPosition(2,1) == Player::PIECE && $board->getPieceAtPosition(2,2) == Player::PIECE
            || $board->getPieceAtPosition(0,0) == Player::PIECE && $board->getPieceAtPosition(1,0) == Player::PIECE && $board->getPieceAtPosition(2,0) == Player::PIECE
            || $board->getPieceAtPosition(0,1) == Player::PIECE && $board->getPieceAtPosition(1,1) == Player::PIECE && $board->getPieceAtPosition(2,1) == Player::PIECE
			|| $board->getPieceAtPosition(0,2) == Player::PIECE && $board->getPieceAtPosition(1,2) == Player::PIECE && $board->getPieceAtPosition(2,2) == Player::PIECE
			|| $board->getPieceAtPosition(0,0) == Player::PIECE && $board->getPieceAtPosition(1,1) == Player::PIECE && $board->getPieceAtPosition(2,2) == Player::PIECE
			|| $board->getPieceAtPosition(0,2) == Player::PIECE && $board->getPieceAtPosition(1,1) == Player::PIECE && $board->getPieceAtPosition(2,0) == Player::PIECE
        )
            return Player::PIECE;
		else if ($board->getPieceAtPosition(0,0) == AI::PIECE && $board->getPieceAtPosition(0,1) == AI::PIECE && $board->getPieceAtPosition(0,2) == AI::PIECE
            || $board->getPieceAtPosition(1,0) == AI::PIECE && $board->getPieceAtPosition(1,1) == AI::PIECE && $board->getPieceAtPosition(1,2) == AI::PIECE
            || $board->getPieceAtPosition(2,0) == AI::PIECE && $board->getPieceAtPosition(2,1) == AI::PIECE && $board->getPieceAtPosition(2,2) == AI::PIECE
            || $board->getPieceAtPosition(0,0) == AI::PIECE && $board->getPieceAtPosition(1,0) == AI::PIECE && $board->getPieceAtPosition(2,0) == AI::PIECE
            || $board->getPieceAtPosition(0,1) == AI::PIECE && $board->getPieceAtPosition(1,1) == AI::PIECE && $board->getPieceAtPosition(2,1) == AI::PIECE
			|| $board->getPieceAtPosition(0,2) == AI::PIECE && $board->getPieceAtPosition(1,2) == AI::PIECE && $board->getPieceAtPosition(2,2) == AI::PIECE
			|| $board->getPieceAtPosition(0,0) == AI::PIECE && $board->getPieceAtPosition(1,1) == AI::PIECE && $board->getPieceAtPosition(2,2) == AI::PIECE
			|| $board->getPieceAtPosition(0,2) == AI::PIECE && $board->getPieceAtPosition(1,1) == AI::PIECE && $board->getPieceAtPosition(2,0) == AI::PIECE
        )
			return AI::PIECE;
		else
			return false;
    }

    /**
     * @param int $y Row
     * @param int $x Column
     * @return string
     */
    public function getPieceAtPosition($y, $x)
    {
        return $this->pieces[$y][$x];
    }

    /**
     * @param int $y
     * @param int $x
     * @return bool
     */
    public function hasPieceAtPosition($y, $x)
    {
        return $this->getPieceAtPosition($y, $x) !== '';
    }

}
/**
 * Gets input from the player and places pieces on the board.
 * Contains some minimal validation to prevent:
 * * Placing pieces on top of pieces
 * * Placing pieces outside of the board
 */
class Player
{
    const PIECE = 'O';

    public function placePiece(Board $board)
    {
        list($y, $x) = $this->getMovePosition($board);
        $board->addPiece($y, $x, self::PIECE);
		$retPos = new stdClass;
		$retPos->row = $y;
		$retPos->column = $x;
		return $retPos;
    }

    private function getMovePosition(Board $board)
    {
        do {
            $position = [
                $this->getInput('Row'),
                $this->getInput('Col')
            ];
			//echo $board->hasPieceAtPosition(2,2);
            $valid = !$board->hasPieceAtPosition($position[0], $position[1]);

            if (!$valid) {
                echo "Piece already at this position\n";
            }

        } while (!$valid);
        return $position;
    }

    private function getInput($prompt)
    {
        echo "$prompt: ";

        do {
            $input = readline();

            $valid = $input >= 0 && $input <= 2;

            if (!$valid) {
                echo "Input must be in range [0, 2]\n";
            }

        } while (!$valid);

        return $input;
    }
}

/**
 * Enemy player. Automatically places pieces on the board according to some rules.
 */
class AI
{
    const PIECE = 'X';

    /**
     * This function should call:
     *
     * $board->addPiece($y, $x, self::PIECE);
     *
     * use getMovePosition
     *
     * @param Board $board
     */
    public function placePiece(Board $board)
    {
        $this->getMovePosition($board);
		/*
		$valid = !$board->hasPieceAtPosition($y, $x);
		if (!$valid) { 
			//Piece already at this position
		} else { $board->addPiece($y, $x, self::PIECE); }
		*/
    }

    /**
     * Return the position the AI wants to place a piece at
     *
     * This function should return [$y, $x] or null
     *
     * $y is the 0-index row
     * $x is the 0-index column
     *
     * The AI should use this logic in order:
     * 1. Place a piece that makes the AI win
     * 2. Or place a piece that prevents the player from winning
     * 3. Or place the piece in any position
     *
     * @param Board $board
     * @return int[]|null
     */
    private function getMovePosition(Board $board)
    {
		$blank_spaces = $board->getBlankPositions($board);
		$position = NULL;
		try {
			$this->goForWin($board, false);
		} catch (\Exception $e) {
			try {
				$this->preventLoss($board);
			} catch (\Exception $e) {
				if (isset($blank_spaces)) {
					$blank_space_count = sizeof($blank_spaces);
					$rand_chooser = rand(0, $blank_space_count-1);
					$board->addPiece($blank_spaces[$rand_chooser][0], $blank_spaces[$rand_chooser][1], AI::PIECE);
					$position = $blank_spaces[$rand_chooser];
				}
			}
		}
        return $position;
    }
	
	/**
	 * @throws Exception If the AI does not go for the win
	 */
	function goForWin($board, $isBlocking)
	{
		if ($isBlocking == true)
			$pieceGet = Player::PIECE;
		else
			$pieceGet = AI::PIECE;
		
		$foundWinner = false;
		$blank_spaces = $board->getBlankPositions($board);
		$movePos = new stdclass;
		
		foreach ($blank_spaces as $blank_space) {
			//attempt win at row |
			$foundWinner = test_row($board, $movePos, $pieceGet, $blank_space);
			if ($foundWinner != NULL) {
				$board->addPiece($foundWinner[0], $foundWinner[1], AI::PIECE);
				$foundWinner = true;
				break;
			}
			//attempt win at column -
			$foundWinner = test_col($board, $movePos, $pieceGet, $blank_space);
			if ($foundWinner != NULL) {
				$board->addPiece($foundWinner[1], $foundWinner[0], AI::PIECE);
				$foundWinner = true;
				break;
			}
			//attempt win at diag \
			$foundWinner = test_diag1($board, $movePos, $pieceGet, $blank_space);
			if ($foundWinner != NULL) {
				$board->addPiece($foundWinner[0], $foundWinner[1], AI::PIECE);
				$foundWinner = true;
				break;
			}
			//attempt win at diag /
			$foundWinner = test_diag2($board, $movePos, $pieceGet, $blank_space);
			if ($foundWinner != NULL) {
				$board->addPiece($foundWinner[0], $foundWinner[1], AI::PIECE);
				$foundWinner = true;
				break;
			}
			if ($foundWinner == true)
				break;
		}
		if ($foundWinner != true)
			throw new \Exception('AI Did not go for win');
		return $foundWinner;
	}
	
	/**
	 * @throws Exception If the AI does not prevent the player from winning
	 */
	function preventLoss($board)
	{
		$position = $this->goForWin($board, true);
		if ($position != true)
			throw new \Exception('AI Did not prevent Player from winning');
		return $position;
	}
}

/**
 * @returns NULL if AI cannot win the any rows and player cannot win any rows
 */
function test_row($board, $movePos, $pieceGet, $blank_space)
{
	$ai_pieces = 0; 
	$movePos->row = $blank_space[0];
	$movePos->column = $blank_space[1];
	for ($i = 0; $i < 3; $i++) {
		if ($board->getPieceAtPosition($movePos->row, $i) == $pieceGet) {
			$ai_pieces++;
		}
		if ($ai_pieces > 1) {
			for ($j = 0; $j < 3; $j++) {
				if ($board->getPieceAtPosition($movePos->row, $j) == '') {
					$ret[0] = $movePos->row;
					$ret[1] = $j;
					return $ret;
				}
			}
		}
	}
	return NULL;
}

/**
 * @returns NULL if AI cannot win the any columns and player cannot win any columns
 */
function test_col($board, $movePos, $pieceGet, $blank_space)
{
	$ai_pieces = 0;
	$movePos->row = $blank_space[0];
	$movePos->column = $blank_space[1];
	for ($i = 0; $i < 3; $i++) {
		if ($board->getPieceAtPosition($i, $movePos->column) == $pieceGet) {
			$ai_pieces++;
		}
		if ($ai_pieces > 1) {
			for ($j = 0; $j < 3; $j++) {
				if ($board->getPieceAtPosition($j, $movePos->column) == '') {
					$ret[0] = $movePos->column;
					$ret[1] = $j;
					return $ret;
				}
			}
		}
	}
	return NULL;
}

/**
 * @returns NULL if AI cannot win the any diags \ and player cannot win any diags \
 */
function test_diag1($board, $movePos, $pieceGet, $blank_space)
{
	$ai_pieces = 0;
	$movePos->row = $blank_space[0];
	$movePos->column = $blank_space[1];
	if ($movePos->column == $movePos->row) {
		//we're on a diagonal
		for ($i = 0; $i < 3; $i++) {
			if ($board->getPieceAtPosition($i, $i) == $pieceGet) {
				$ai_pieces++;
			}
			if ($ai_pieces > 1) {
				for ($j = 0; $j < 3; $j++) {
					if ($board->getPieceAtPosition($j, $j) == '') {
						$ret[0] = $j;
						$ret[1] = $j;
						return $ret;
					}
				}
			}
		}
	}
	return NULL;
}

/**
 * @returns NULL if AI cannot win the any diags / and player cannot win any diags /
 */
function test_diag2($board, $movePos, $pieceGet, $blank_space)
{
	$ai_pieces = 0;
	$movePos->row = $blank_space[0];
	$movePos->column = $blank_space[1];
	if ($movePos->column + $movePos->row == 3-1) {
		//we're on another diagonal
		for ($i = 0; $i < 3; $i++) {
			if ($board->getPieceAtPosition($i, (3 - 1)-$i) == $pieceGet) {
				$ai_pieces++;
			}
			if ($ai_pieces > 1) {
				for ($j = 0; $j < 3; $j++) {
					if ($board->getPieceAtPosition($j, (3 - 1)-$j) == '') {
						$ret[0] = $j;
						$ret[1] = (3 - 1)-$j;
						return $ret;
					}
				}
			}
		}
	}
	return NULL;
}

/**
 * @throws Exception If the AI does not go for the win
 */
function test_go_for_win()
{
    $board = new Board;
    $ai    = new AI;
    $board->addPiece(0, 0, AI::PIECE);
    $board->addPiece(0, 1, AI::PIECE);
    $ai->placePiece($board);
    if ($board->getPieceAtPosition(0, 2) !== AI::PIECE) {
        throw new \Exception('AI Should go for win');
    }
}

/**
 * @throws Exception If the AI does not prevent the player from winning
 */
function test_prevent_loss()
{
	$board = new Board;
	$ai    = new AI;
	$board->addPiece(0, 2, Player::PIECE);
	$board->addPiece(1, 1, Player::PIECE);
	$ai->placePiece($board);
	if ($board->getPieceAtPosition(2, 0) !== AI::PIECE) {
		throw new \Exception('AI Should try to prevent player from winning');
	}
}

function debug_board($board)
{
    return str_replace("\n", " NEW LINE\n", $board);
}

/**
 * The board should be displayed in a grid.
 * A blank space: -
 * A piece: X or O
 *
 * Three columns in three rows
 *
 * Examples:
 * space: [row, column, X or O]
 * where data is a list of spaces
 *
 * data: [ [0, 0, O], [1, 0, O], [2, 0, O] ]
 * O--
 * O--
 * O--
 *
 * data: [ [1, 1, X] ]
 * ---
 * -X-
 * ---
 *
 * @throws Exception If board is rendered incorrectly.
 */
function test_display_board()
{
    $board = new Board;

    $board->addPiece(0, 0, 'O');
    $board->addPiece(1, 1, 'O');
    $board->addPiece(2, 2, 'O');

    $actual = (string)$board;

    $expected = "O--\n-O-\n--O\n";

    if ($actual !== $expected) {
        echo "Expected:\n", debug_board($expected);
        echo "\nActual:\n", debug_board($actual), "\n";
        throw new \Exception('test_display_board: Board should render correctly');
    }
}

/**
 * @throws Exception When a winning piece combination does not trigger a win
 */
function test_player_wins()
{
    $board = new Board;
	$player = new Player;
	$movePos = new stdclass;
    $board->addPiece(0, 0, Player::PIECE);
    $board->addPiece(1, 0, Player::PIECE);
	$board->addPiece(2, 0, Player::PIECE);
    $movePos->row = 2; $movePos->column = 0;
	//$movePos = $player->placePiece($board); //manual input testing
    if ($board->getWinner($board, $movePos) !== Player::PIECE) {
        throw new \Exception("Player should be able to win");
    }
}

function run_tests()
{
    test_display_board();
    test_player_wins();
    test_go_for_win();
    test_prevent_loss();
}

try {
    run_tests();
	init_GameLoop();
} catch (\Exception $e) {
    echo "Tests failed, game aborted\nMessage: ", $e->getMessage(), "\n";
    exit(1);
}

/*
 * TODO Game Loop
 *
 * The game should be running if
 * 1. Nobody has won
 * 2. And the board is not full
 *
 * The AI goes first
 *
 * After the AI places a piece, display the board using the __toString method
 *
 * After someone places a piece, check if they won.
 *
 * If someone won display the message and exit
 *
 * If the board is full, display a message and exit
 */
function init_GameLoop() {
	$board  = new Board;
	$ai     = new AI;
	$player = new Player;
	$movePos = new stdclass;
	while (!empty($board->getBlankPositions($board))) {
		//AI moves first
		$movePos = $ai->placePiece($board);
		//if board is full (no one has won)
		if (empty($board->getBlankPositions($board))) {
			echo "\nBoard is full: TIE!\n";
			$actual = (string)$board;
			echo "\nBoard:\n", debug_board($actual), "\n";
			exit();
		}
		$actual = (string)$board;
		echo "\nBoard:\n", debug_board($actual), "\n";
		//check if the Player or AI has won
		$PlayerOrAIWinner = $board->getWinner($board, $movePos);
		if ($PlayerOrAIWinner != NULL) {
			echo "\n".$PlayerOrAIWinner." WINS!\n";
			$actual = (string)$board;
			echo "\nBoard:\n", debug_board($actual), "\n";
			exit();
		}
		//Player moves second
		$movePos = $player->placePiece($board);
		//check if the Player or AI has won
		$PlayerOrAIWinner = $board->getWinner($board, $movePos);
		if ($PlayerOrAIWinner != NULL) {
			echo "\n".$PlayerOrAIWinner." WINS!\n";
			$actual = (string)$board;
			echo "\nBoard:\n", debug_board($actual), "\n";
			exit();
		}
	}
}
?>