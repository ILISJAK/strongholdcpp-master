import Camera from './modules/Camera.js';
import Game from './modules/Game.js';

document.addEventListener("DOMContentLoaded", () => {
    let gameContent = document.getElementById("game-content");
    let camera = new Camera(gameContent); // Initialize the Camera

    let game = new Game(camera);
    game.start();
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        if (!menuOpen) {
            showMenu();
        }
        else {
            closeMenu();
        }
        event.preventDefault();
    }
});

let menuOpen = false;

function showMenu() {
    // Create a modal or overlay element to display the menu
    var overlay = document.createElement('div');
    overlay.className = 'overlay';

    var menu = document.createElement('div');
    menu.className = 'menu';

    var saveButton = document.createElement('button');
    saveButton.className = 'button';
    saveButton.textContent = 'Save Game';
    saveButton.addEventListener('click', () => {
        saveGame();
        closeMenu();
    });

    var loadButton = document.createElement('button');
    loadButton.className = 'button';
    loadButton.textContent = 'Load Game';
    loadButton.addEventListener('click', fetchSavedGames);

    var quitButton = document.createElement('button');
    quitButton.textContent = 'Quit Game';
    quitButton.className = 'button';
    quitButton.addEventListener('click', quitGame);

    menu.appendChild(saveButton);
    menu.appendChild(loadButton);
    menu.appendChild(quitButton);
    overlay.appendChild(menu);

    document.body.appendChild(overlay);
    menuOpen = true;
}

function quitGame() {
    window.location.href = 'mainmenu.php';
    console.log('Game quit');
    closeMenu();
}

function closeMenu() {
    // Remove the overlay and menu from the DOM
    var overlay = document.querySelector('.overlay');
    overlay.parentNode.removeChild(overlay);
    menuOpen = false;
}

async function fetchSavedGames() {
    try {
        const response = await fetch('../../api/load_game.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`Failed to fetch saved games. Status: ${response.status}`);
        }

        const savedGames = await response.json();

        // Create and show dialog for loading saved games
        showDialog(savedGames);
    } catch (error) {
        console.error('Error fetching saved games from server:', error);
    }
}

function createOverlay() {
    var overlay = document.createElement('div');
    overlay.className = 'overlay';
    return overlay;
}

function closeOverlay() {
    var overlay = document.querySelector('.overlay');
    if (overlay) {
        overlay.parentNode.removeChild(overlay);
    }
}

function showDialog(savedGames) {
    closeOverlay();
    var overlay = createOverlay();
    overlay.className = 'overlay';

    var loadDialog = document.createElement('div');
    loadDialog.className = 'load-dialog';

    var select = document.createElement('select');
    select.id = 'saved-games-select';

    for (let game of savedGames) {
        var option = document.createElement('option');
        option.value = game.id;
        option.textContent = `Game ID: ${game.id}, Save Date: ${game.save_date}`;
        select.appendChild(option);
    }

    var loadButton = document.createElement('button');
    loadButton.className = 'button';
    loadButton.textContent = 'Load Selected Game';
    loadButton.addEventListener('click', () => {
        var selectedGameId = document.getElementById('saved-games-select').value;
        loadGame(selectedGameId);
        closeMenu();
    });

    loadDialog.appendChild(select);
    loadDialog.appendChild(loadButton);
    overlay.appendChild(loadDialog);

    document.body.appendChild(overlay);
}

async function saveGame() {
    // Making a request to save_game.php to save the game data in the session to the database
    try {
        const response = await fetch('../game/save_game.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`Failed to save game. Status: ${response.status}`);
        }

        const responseBody = await response.text();
        console.log('Game saved successfully:', responseBody);
    } catch (error) {
        console.error('Error saving game to server:', error);
    }
}

async function loadGame(selectedGameId) {
    // Loading game data from server
    try {
        const response = await fetch(`../../api/load_game.php?gameId=${selectedGameId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`Failed to load game. Status: ${response.status}`);
        }

        const gameData = await response.json();
        console.log('Game loaded successfully:', gameData);

        // Redirect to the game instance page or refresh the game
        window.location.href = "../../src/views/game_instance.php";

    } catch (error) {
        console.error('Error loading game from server:', error);
    }
}


