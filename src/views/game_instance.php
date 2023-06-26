<?php session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../../index.php");
    exit;
}

echo "<script>console.log('Session Data: ', " . json_encode($_SESSION) . ");</script>";

// ]
?>

<!DOCTYPE html>
<html>

<head>
    <title>Game Instance</title>
    <link rel="stylesheet" type="text/css" href="../../public_html/css/game_instance.css">
    <link rel="stylesheet" type="text/css" href="../../public_html/css/modules/iziToast.min.css">
</head>

<body>
    <!-- Display current turn -->
    <div id="current-turn">Turn: 1 (Player A)</div>

    <div id="game-board">
        <div id="game-content">
            <!-- Other game content -->
            <!-- <canvas id="game-canvas"></canvas> -->
        </div>
    </div>

    <!-- Dialog Overlay -->
    <div id="dialog-overlay"></div>

    <!-- Dialog for town actions -->
    <div id="town-dialog" class="dialog-box">
        <h3>CHOOSE ACTION</h3>
        <button type="button"
            onclick="console.log('Button clicked'); document.dispatchEvent( new CustomEvent('openTrainVillagersDialog'));">Train
            Villagers</button>
        <button type="button"
            onclick="console.log('Button clicked'); document.dispatchEvent( new CustomEvent('openTrainTroopDialog'));">Train
            Troop</button>
        <button type="button"
            onclick="console.log('Button clicked'); document.dispatchEvent( new CustomEvent('openBuildStructureDialog'));">Build
            Structure</button>
        <button type="button"
            onclick="console.log('Button clicked'); document.dispatchEvent( new CustomEvent('openBuildHousingDialog'));">Build
            Housing</button>
        <button type="button"
            onclick="console.log('Button clicked'); document.dispatchEvent( new CustomEvent('openMarket'));">Market</button>

    </div>

    <div id="train-villagers-dialog" class="dialog-box">
        <h3>Train Villager</h3>
        <form id="train-villagers-form">
            <label for="villager-amount">Number of Villagers:</label>
            <input type="number" id="villager-amount" min="1" value="1">
            <button type="submit">Train Villagers</button>
        </form>
    </div>
    <div id="train-troop-dialog" class="dialog-box">
        <h3>Train troop</h3>
        <form id="train-troop-form">
            <label for="troop-amount">Number of troops:</label>
            <input type="number" id="troop-amount" min="1" value="1">
            <label for="troop-type">Type of troop:</label>
            <select name="troop-type" id="troop-type">
                <option value="Archer">Archer</option>
                <option value="Knight">Knight</option>
                <option value="Maceman">Maceman</option>
                <option value="Pikeman">Pikeman</option>
            </select>
            <button type="submit">Train Troop</button>
        </form>
    </div>
    <div id="build-structure-dialog" class="dialog-box">
        <h3>Build Structure</h3>
        <form id="build-structure-form">
            <label for="structure-amount">Build structure:</label>
            <input type="number" id="structure-amount" min="1" value="1">
            <label for="structure-type">Structure type:</label>
            <select id="structure-type">
                <option value="WoodCamp">Wood Camp</option>
                <option value="Market">Market</option>
                <option value="WheatFarm">Wheat Farm</option>
                <option value="StoneQuarry">Stone Quarry</option>
            </select>
            <button type="submit">Build Structure</button>
        </form>
    </div>
    <div id="build-housing-dialog" class="dialog-box">
        <h3>Build Housing</h3>
        <form id="build-housing-form">
            <label for="housing-amount">Number of houses:</label>
            <input type="number" id="housing-amount" min="1" value="1">
            <button type="submit">Build Housing</button>
        </form>
    </div>
    <div id="market-dialog" class="dialog-box">
        <h3>Market</h3>
        <form onsubmit="return false" id="market-form">
            <label for="resource-type">Resource:</label>
            <select id="resource-type">
                <option value="rations">Rations</option>
                <option value="wood">Wood</option>
                <option value="stone">Stone</option>
            </select>
            <label for="resource-amount">Amount:</label>
            <input type="number" id="resource-amount" min="1" value="1">
            <button type="submit" id="buy-button">Buy</button>
            <button type="submit" id="sell-button">Sell</button>
        </form>
    </div>

    <div id="town-info-dialog" class="modal">
        <div class="modal-content">
            <!-- <span class="close-btn">&times;</span> -->
            <!-- <p id="town-info"></p> -->
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../public_html/js/modules/iziToast.min.js"></script>
    <script src="../../vendor/noisejs/perlin.js"></script>
    <script type="module" src="../../public_html/js/modules/Camera.js"></script>
    <script type="module" src="../../public_html/js/modules/TerrainGenerator.js"></script>
    <script type="module" src="../../public_html/js/game_instance.js"></script>
    <script>
        let username = <?php echo json_encode($_SESSION['username'] ?? 'default_username'); ?>;
        let user_id = <?php echo json_encode($_SESSION['user_id'] ?? 'default_user_id'); ?>;
        console.log("Username: ", username, " user_id: ", user_id);
    </script>
</body>

</html>