import TerrainGenerator from './TerrainGenerator.js';

export default class Game {
    constructor(camera) {
        this.towns = [];
        this.camera = camera;
        this.canvas = null;
        this.ctx = null;
        this.tilesetImage = null;
        this.terrainGenerator = null;
        this.tileSize = 16;
        this.tilesetColumns = 8;
        this.mapWidth = 1000;
        this.mapHeight = 1000;
        this.dialogOverlay = null;
        this.townDialog = null;
        this.trainVillagersDialog = null;
        this.actionForm = null;
        this.trainVillagersForm = null;
        this.villagerAmountInput = null;
        this.troopAmountInput = null;
        this.troopTypeInput = null;
        this.structureAmountInput = null;
        this.structureTypeInput = null;
        this.housingAmountInput = null;
        this.numPlayers = null;
        this.currentPlayer = null;
        this.fetchCurrentState();

    }

    start() {
        this.initCanvas();
        this.initTerrainGenerator();
        this.loadResources();
        this.setupEventListeners();
        this.drawTerrain();

        // Retrieve the number of players from the session
        this.numPlayers = parseInt(sessionStorage.getItem("numPlayers"), 10);

        // Check if numPlayers is a valid number
        if (!isNaN(this.numPlayers)) {
            // Generate towns based on the number of players
            this.generateTowns(this.numPlayers);
        } else {
            console.error("Number of players is not valid.");
        }
    }

    initCanvas() {
        let gameContent = document.getElementById("game-content");
        this.canvas = document.createElement("canvas");
        this.ctx = this.canvas.getContext("2d");
        this.canvas.width = 6000;
        this.canvas.height = 6000;
        gameContent.appendChild(this.canvas);
    }

    initTerrainGenerator() {
        this.terrainGenerator = new TerrainGenerator();
    }

    loadResources() {
        this.tilesetImage = new Image();
        this.tilesetImage.src = "../../public_html/img/tiles/Tileset.png";
        this.tilesetImage.onload = () => {
            this.drawTerrain();
        };
    }

    generateTerrain() {
        const terrainData = this.terrainGenerator.generateTerrain(this.mapWidth, this.mapHeight);
        return terrainData;
    }

    drawTerrain() {
        const terrainData = this.generateTerrain();

        for (let y = 0; y < this.mapHeight; y++) {
            for (let x = 0; x < this.mapWidth; x++) {
                const dirtTileIndex = this.terrainGenerator.getRandomDirtTile();
                const tilesetX = (dirtTileIndex % this.tilesetColumns) * this.tileSize;
                const tilesetY = Math.floor(dirtTileIndex / this.tilesetColumns) * this.tileSize;

                this.ctx.drawImage(
                    this.tilesetImage,
                    tilesetX,
                    tilesetY,
                    this.tileSize,
                    this.tileSize,
                    x * this.tileSize,
                    y * this.tileSize,
                    this.tileSize,
                    this.tileSize
                );
            }
        }

        for (let y = 0; y < this.mapHeight; y++) {
            for (let x = 0; x < this.mapWidth; x++) {
                const tileIndex = terrainData.terrain[y][x];

                if (tileIndex === undefined || this.terrainGenerator.isDirtTile(tileIndex)) {
                    continue;
                }

                const tilesetX = (tileIndex % this.tilesetColumns) * this.tileSize;
                const tilesetY = Math.floor(tileIndex / this.tilesetColumns) * this.tileSize;

                this.ctx.drawImage(
                    this.tilesetImage,
                    tilesetX,
                    tilesetY,
                    this.tileSize,
                    this.tileSize,
                    x * this.tileSize,
                    y * this.tileSize,
                    this.tileSize,
                    this.tileSize
                );
            }
        }

        for (let y = 0; y < this.mapHeight; y++) {
            for (let x = 0; x < this.mapWidth; x++) {
                const tileIndex = terrainData.overlay[y][x];

                if (tileIndex === null) {
                    continue;
                }

                const tilesetX = (tileIndex % this.tilesetColumns) * this.tileSize;
                const tilesetY = Math.floor(tileIndex / this.tilesetColumns) * this.tileSize;

                this.ctx.drawImage(
                    this.tilesetImage,
                    tilesetX,
                    tilesetY,
                    this.tileSize,
                    this.tileSize,
                    x * this.tileSize,
                    y * this.tileSize,
                    this.tileSize,
                    this.tileSize
                );
            }
        }
    }

    setupEventListeners() {
        this.dialogOverlay = document.getElementById("dialog-overlay");
        this.townDialog = document.getElementById("town-dialog");
        this.trainVillagersDialog = document.getElementById("train-villagers-dialog");
        this.trainTroopDialog = document.getElementById("train-troop-dialog");
        this.buildStructureDialog = document.getElementById("build-structure-dialog");
        this.buildHousingDialog = document.getElementById("build-housing-dialog");
        this.marketDialog = document.getElementById("train-villagers-dialog");
        this.actionForm = document.getElementById("action-form");

        this.dialogOverlay.addEventListener("click", () => {
            this.closeDialogs();
        });

        // Listen for the custom event
        document.addEventListener('openTrainVillagersDialog', () => {
            console.log('Custom event received');
            this.openTrainVillagersForm();
        });
        document.addEventListener('openTrainTroopDialog', () => {
            console.log('Custom event received');
            this.openTrainTroopForm();
        });

        document.addEventListener('openBuildStructureDialog', () => {
            console.log('Custom event received');
            this.openBuildStructureForm();
        });
        document.addEventListener('openBuildHousingDialog', () => {
            console.log('Custom event received');
            this.openBuildHousingForm();
        });

        document.addEventListener('openMarket', () => {
            console.log('Custom event received');
            this.openMarketForm();
        });



        let trainVillagersFormContainer = this.trainVillagersDialog.querySelector("#train-villagers-form-container");

        if (trainVillagersFormContainer) {
            this.trainVillagersForm = trainVillagersFormContainer.querySelector("form");
            this.villagerAmountInput = this.trainVillagersForm.querySelector("#villager-amount");

            if (this.trainVillagersForm) {
                this.trainVillagersForm.addEventListener("submit", (event) => {
                    event.preventDefault();
                    this.handleTrainVillagersForm();
                });
            }
        }

        let trainVillagersForm = this.trainVillagersDialog.querySelector("form");
        if (trainVillagersForm) {
            trainVillagersForm.addEventListener("submit", (event) => {
                // Prevent the default form submission behavior
                event.preventDefault();

                // Handle the form submission
                this.handleTrainVillagersForm();
            });
        }
        let trainTroopFormContainer = this.trainTroopDialog.querySelector("#train-troop-form-container");

        if (trainTroopFormContainer) {
            this.trainTroopForm = trainTroopFormContainer.querySelector("form");
            this.troopAmountInput = this.trainTroopForm.querySelector("#troop-amount");
            this.troopTypeInput = this.trainTroopForm.querySelector("#troop-type");

            if (this.trainTroopForm) {
                this.trainTroopForm.addEventListener("submit", (event) => {
                    event.preventDefault();
                    this.handleTrainTroopForm();
                });
            }
        }

        let trainTroopForm = this.trainTroopDialog.querySelector("form");
        if (trainTroopForm) {
            trainTroopForm.addEventListener("submit", (event) => {
                // Prevent the default form submission behavior
                event.preventDefault();

                // Handle the form submission
                this.handleTrainTroopForm();
            });
        }
        let buildStructureFormContainer = this.buildStructureDialog.querySelector("#build-structure-form-container");

        if (buildStructureFormContainer) {
            this.buildStructureForm = buildStructureFormContainer.querySelector("form");
            this.structureAmountInput = this.buildStructureForm.querySelector("#structure-amount");
            this.structureTypeInput = this.buildStructureForm.querySelector("#structure-type");

            if (this.buildStructureForm) {
                this.buildStructureForm.addEventListener("submit", (event) => {
                    event.preventDefault();
                    this.handleBuildStructureForm();
                });
            }
        }


        let buildStructureForm = this.buildStructureDialog.querySelector("form");
        if (buildStructureForm) {
            buildStructureForm.addEventListener("submit", (event) => {
                // Prevent the default form submission behavior
                event.preventDefault();

                // Handle the form submission
                this.handleBuildStructureForm();
            });
        }
        let buildHousingFormContainer = this.buildHousingDialog.querySelector("#build-housing-form-container");

        if (buildHousingFormContainer) {
            this.buildHousingForm = buildHousingFormContainer.querySelector("form");
            this.housingAmountInput = this.buildHousingForm.querySelector("#housing-amount");

            if (this.buildHousingForm) {
                this.buildHousingForm.addEventListener("submit", (event) => {
                    event.preventDefault();
                    this.handleBuildHousingForm();
                });
            }
        }

        let buildHousingForm = this.buildHousingDialog.querySelector("form");
        if (buildHousingForm) {
            buildHousingForm.addEventListener("submit", (event) => {
                event.preventDefault();

                // Handle the form submission
                this.handleBuildHousingForm();
            });
        }

        let townContainers = document.getElementsByClassName("town-container");
        for (let i = 0; i < townContainers.length; i++) {
            townContainers[i].addEventListener("click", (event) => {
                event.preventDefault();
                console.log("Town container clicked.");
                this.openTownDialog();
            }, true);
        }
    }

    openTrainVillagersForm() {
        console.log('Opening Train Villagers Form');
        this.openDialog(this.trainVillagersDialog);
    }
    openTrainTroopForm() {
        console.log('Opening Train Troop Form');
        this.openDialog(this.trainTroopDialog);
    }
    openBuildStructureForm() {
        console.log('Opening Train Villagers Form');
        this.openDialog(this.buildStructureDialog);
    }
    openBuildHousingForm() {
        console.log('Opening Train Villagers Form');
        this.openDialog(this.buildHousingDialog);
    }
    openMarketForm() {
        console.log('Opening Train Villagers Form');
        this.openDialog(this.marketDialog);
    }

    async getTownData(playerIdentifier) {
        const properties = ["population", "activePopulation", "gold", "rations", "wood", "stone", "housing"];
        const townData = { playerIdentifier };

        const url = '../../api/get_property.php';

        try {
            const promises = properties.map(async (property) => {
                const data = {
                    playerIdentifier: playerIdentifier,
                    entityType: 'town',
                    property: property
                };

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                console.log('Raw response:', response);

                if (!response.ok) {
                    throw new Error(`Error fetching town data for ${property}: ${response.statusText}`);
                }

                const responseText = await response.text();

                try {
                    const responseData = JSON.parse(responseText);
                    townData[property] = responseData.value;
                    console.log(`Retrieved ${property} data:`, responseData);
                } catch (error) {
                    console.error('Error parsing JSON:', error);
                    console.log('Response Text:', responseText);
                }

            });

            await Promise.all(promises);

            // Save town data to session
            await fetch('../../src/game/session.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(townData)
            });

            return townData;
        } catch (error) {
            console.error('Error fetching town data:', error);
        }
    }

    fetchCurrentState() {
        // Fetch current game state from the server
        fetch('../game/get_current_state.php').then(response => {
            return response.json();
        }).then(gameState => {
            // Log the gameState to see what data it contains
            console.log("Received game state:", gameState);

            // Update the UI with the current game state
            document.getElementById("current-turn").innerHTML = `Turn: ${gameState.turnNumber} (Player ${gameState.playerIdentifier})`;
            this.currentPlayer = gameState.playerIdentifier;

            console.log(`Current player set to: ${this.currentPlayer}`);
        });
    }




    // Add a new method to generate towns
    generateTowns(numPlayers) {
        let gameContent = document.getElementById("game-content");

        // Generate towns based on the number of players
        for (let i = 0; i < numPlayers; i++) {
            let townContainer = document.createElement("div");
            townContainer.classList.add("town-container");

            // Use letters instead of numbers
            let playerIdentifier = String.fromCharCode(65 + i);

            // Set the data-player-identifier attribute on townContainer
            townContainer.setAttribute("data-player-identifier", playerIdentifier);

            let townLabel = document.createElement("div");
            townLabel.classList.add("town-label");
            townLabel.innerText = playerIdentifier;
            townContainer.appendChild(townLabel);

            // Create the town info modal
            let townInfoModal = document.createElement("div");
            townInfoModal.classList.add("town-info-modal");
            townInfoModal.id = `town-info-${playerIdentifier}`;
            townContainer.appendChild(townInfoModal);

            let town = document.createElement("img");
            town.src = "../../public_html/img/castle.png";
            town.classList.add("element");

            let townSize = 150;
            town.style.width = `${townSize}px`;
            town.style.height = `${townSize}px`;

            let hueRotation = Math.random() * 360;
            town.style.filter = `hue-rotate(${hueRotation}deg)`;

            townContainer.appendChild(town);

            townContainer.addEventListener("click", () => {
                if (playerIdentifier == this.currentPlayer) {
                    console.log("Current player: ", this.currentPlayer);
                    iziToast.show({
                        title: 'Alert',
                        message: `Player ${this.currentPlayer} is accessing ${playerIdentifier} town.`,
                        position: 'topLeft',
                        timeout: 3000
                    });
                    this.openTownDialog();
                }
                else {
                    iziToast.show({
                        title: 'Alert',
                        message: `Player ${this.currentPlayer} is trying to access ${playerIdentifier} town.`,
                        position: 'topLeft',
                        timeout: 3000,
                        backgroundColor: '#FF0000'
                    });
                }
            });

            // Set the position on the townContainer instead of the town image
            townContainer.style.left = `${Math.random() * 1000 * 5}px`;
            townContainer.style.top = `${Math.random() * 1000 * 5}px`;

            gameContent.appendChild(townContainer);

            // Populate town info modal
            this.populateTownInfoModal(playerIdentifier);

            console.log(`Generated town for player ${playerIdentifier}`);
            console.log(`Town container:`, townContainer);
            console.log(`Town label:`, townLabel);
            console.log(`Town info modal:`, townInfoModal);
            console.log(`Town image:`, town);
        }
    }


    populateTownInfoModal(playerIdentifier) {
        console.log(`Populating town info modal for player: ${playerIdentifier}`);
        const townContainer = document.querySelector(`.town-container[data-player-identifier="${playerIdentifier}"]`);
        const townInfoModal = townContainer ? townContainer.querySelector('.town-info-modal') : null;

        if (townContainer && townInfoModal) {
            this.getTownData(playerIdentifier).then((townData) => {
                if (townData) {
                    let infoHTML = "";
                    for (const [key, value] of Object.entries(townData)) {
                        infoHTML += `<b>${key}:</b> ${value}<br>`;
                    }
                    townInfoModal.innerHTML = infoHTML;
                } else {
                    townInfoModal.innerHTML = "Error fetching town data.";
                }
            });

            townInfoModal.style.display = "block";
        } else {
            console.error("Town container or town info modal not found.");
        }
    }


    openDialog(dialog) {
        console.log('Attempting to open dialog:', dialog);
        this.dialogOverlay.style.display = "block";
        dialog.style.display = "block";
    }

    closeDialogs() {
        this.dialogOverlay.style.display = "none";
        const dialogs = document.querySelectorAll('.dialog-box');
        for (let dialog of dialogs) {
            dialog.style.display = "none";
        }
    }

    makeMove(action, data) {
        return new Promise((resolve, reject) => {
            // Send the data to the make_move.php script using a POST request
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "../../api/make_move.php", true);
            xhr.onreadystatechange = () => {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    console.log("PHP response:", xhr.responseText);
                    // Perform any necessary actions after receiving the response
                    const playerIdentifier = this.currentPlayer;
                    console.log(playerIdentifier);
                    this.populateTownInfoModal(playerIdentifier);
                    resolve(xhr.responseText); // Resolve the promise with response
                } else if (xhr.readyState === XMLHttpRequest.DONE) {
                    console.log("Failed with status:", xhr.status);
                    reject(new Error("Failed with status: " + xhr.status)); // Reject the promise
                }
            };

            let formData = new FormData();
            formData.append("playerIdentifier", this.currentPlayer);
            formData.append("action", action);

            // Append additional data to the form data object
            for (let key in data) {
                formData.append(key, data[key]);
            }

            xhr.send(formData);
        });
    }


    handleTrainVillagersForm() {

        let villagerAmountInput = this.trainVillagersDialog.querySelector("#villager-amount");

        if (villagerAmountInput) {
            let villagerAmount = villagerAmountInput.value;

            let data = {
                amount: villagerAmount
            };

            iziToast.show({
                title: 'Success',
                message: `Trained ${data.amount} villager(s).`,
                position: 'topLeft',
                timeout: 3000
            });

            this.makeMove('train-villager', data)
                .then(() => {
                    this.fetchCurrentState();
                })
                .catch((error) => {
                    console.error('Error:', error);
                });

        } else {
            console.error("Cannot find villager-amount input element");
        }
        this.closeDialogs();
    }
    handleTrainTroopForm() {

        let troopAmountInput = this.trainTroopDialog.querySelector("#troop-amount");
        let troopTypeInput = this.trainTroopDialog.querySelector("#troop-type");

        if (troopAmountInput && troopTypeInput) {
            let troopAmount = troopAmountInput.value;
            let troopType = troopTypeInput.value;

            let data = {
                amount: troopAmount,
                entityType: troopType

            };
            console.log(data);

            iziToast.show({
                title: 'Success',
                message: `Trained ${data.amount} ${data.entityType}(s).`,
                position: 'topLeft',
                timeout: 3000
            });

            this.makeMove('train-troop', data);
            this.fetchCurrentState();
        } else {
            console.error("Cannot find troop-amount input element(s)");
        }
        this.closeDialogs();
    }
    handleBuildStructureForm() {

        let structureAmountInput = this.buildStructureDialog.querySelector("#structure-amount");
        let structureTypeInput = this.buildStructureDialog.querySelector("#structure-type");

        if (structureAmountInput && structureTypeInput) {
            let structureAmount = structureAmountInput.value;
            let structureType = structureTypeInput.value;

            let data = {
                amount: structureAmount,
                entityType: structureType

            };
            console.log(data);

            iziToast.show({
                title: 'Success',
                message: `Built ${data.amount} ${data.entityType}(s).`,
                position: 'topLeft',
                timeout: 3000
            });

            this.makeMove('build-structure', data);
            this.fetchCurrentState();
        } else {
            console.error("Cannot find structure input element(s)");
        }
        this.closeDialogs();
    }
    handleBuildHousingForm() {
        let housingAmountInput = this.buildHousingDialog.querySelector("#housing-amount");

        if (housingAmountInput) {
            let housingAmount = housingAmountInput.value;

            let data = {
                amount: housingAmount
            };

            iziToast.show({
                title: 'Success',
                message: `Built ${data.amount} house(s).`,
                position: 'topLeft',
                timeout: 3000
            });

            this.makeMove('build-housing', data);
            this.fetchCurrentState();
        } else {
            console.error("Cannot find housing-amount input element");
        }
        this.closeDialogs();
        return 0;
    }

    openTownDialog() {
        console.log("Opening town dialog.");
        this.dialogOverlay.style.display = "block";
        this.townDialog.style.display = "block";
    }

    closeTownDialog() {
        this.dialogOverlay.style.display = "none";
        this.townDialog.style.display = "none";
    }
}
