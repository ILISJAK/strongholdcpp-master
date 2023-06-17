export default class TerrainGenerator {
    constructor() {
        this.tiles = [
            "grassTopLeft", "grassTopMiddle", "grassTopRight", "",
            "", "", "dirt", "dirt",
            "grassMiddleLeft", "grassCenter", "grassMiddleRight", "",
            "", "", "dirt", "dirt",
            "grassBottomLeft", "grassBottomMiddle", "grassBottomRight", "",
            "", "", "dirt", "dirt",
            "darkGrassTopLeft", "darkGrassTopMiddle", "darkGrassTopRight", "",
            "darkGrassMiddleLeft", "darkGrassCenter", "darkGrassMiddleRight", "",
            "darkGrassBottomLeft", "darkGrassBottomMiddle", "darkGrassBottomRight",
            "grassHillTopLeft", "grassHillTopMiddle", "grassHillTopRight", "",
            "grassHillMiddleLeft", "", "grassHillMiddleRight", "",
            "grassHillBottomLeft", "grassHillBottomMiddle", "grassHillBottomRight"
        ];
    }

    getRandomDirtTile() {
        const dirtTiles = [6, 7, 14, 15, 22, 23]; // Indices for dirt tiles
        return dirtTiles[Math.floor(Math.random() * dirtTiles.length)];
    }
    getRandomGrassCenterTile() {
        const grassCenterTiles = [9, 5, 13, 21]; // Indices for grass center letiations
        return grassCenterTiles[Math.floor(Math.random() * grassCenterTiles.length)];
    }

    isGrassTile(tileIndex) {
        const grassTiles = [9, 5, 13, 21]; // Indices for full grass tiles
        return grassTiles.includes(tileIndex);
    }

    isDirtTile(tileIndex) {
        const dirtTiles = [6, 7, 14, 15, 22, 23]; // Indices for dirt tiles
        return dirtTiles.includes(tileIndex);
    }

    hasNeighbouringGrass(x, y, terrainTiles) {
        const neighbors = [
            { dx: -1, dy: -1 }, { dx: 0, dy: -1 }, { dx: 1, dy: -1 },
            { dx: -1, dy: 0 }, { dx: 1, dy: 0 },
            { dx: -1, dy: 1 }, { dx: 0, dy: 1 }, { dx: 1, dy: 1 }
        ];

        for (let i = 0; i < neighbors.length; i++) {
            let newX = x + neighbors[i].dx;
            let newY = y + neighbors[i].dy;

            if (newX >= 0 && newY >= 0 && newX < terrainTiles.length && newY < terrainTiles[0].length) {
                if (this.isGrassTile(terrainTiles[newX][newY])) {
                    return true;
                }
            }
        }
        return false;
    }

    hasNeighbouringDirt(x, y, terrainTiles) {
        let dx = [-1, 0, 1, -1, 1, -1, 0, 1];
        let dy = [-1, -1, -1, 0, 0, 1, 1, 1];

        for (let i = 0; i < dx.length; i++) {
            let newX = x + dx[i];
            let newY = y + dy[i];

            if (newX >= 0 && newY >= 0 && newX < terrainTiles.length && newY < terrainTiles[0].length) {
                if (this.isDirtTile(terrainTiles[newX][newY])) {
                    return true;
                }
            }
        }
        return false;
    }
    // helper funkcija za odredivanje rubnih i kutnih tileova
    isGrassCornerOrEdgeTile(tileIndex) {
        const cornerAndEdgeTiles = [0, 2, 8, 10, 16, 18];
        return cornerAndEdgeTiles.includes(tileIndex);
    }

    getGrassEdgeTile(x, y, terrainTiles) {
        // Check each side and corner
        const isAboveDirt = y > 0 && this.isDirtTile(terrainTiles[y - 1][x]);
        const isBelowDirt = y < terrainTiles.length - 1 && this.isDirtTile(terrainTiles[y + 1][x]);
        const isLeftDirt = x > 0 && this.isDirtTile(terrainTiles[y][x - 1]);
        const isRightDirt = x < terrainTiles[0].length - 1 && this.isDirtTile(terrainTiles[y][x + 1]);

        // Top Left
        if (isAboveDirt && isLeftDirt) return 0;
        // Top Middle
        if (isAboveDirt) return 1;
        // Top Right
        if (isAboveDirt && isRightDirt) return 2;
        // Middle Left
        if (isLeftDirt) return 8;
        // Middle Right
        if (isRightDirt) return 10;
        // Bottom Left
        if (isBelowDirt && isLeftDirt) return 16;
        // Bottom Middle
        if (isBelowDirt) return 17;
        // Bottom Right
        if (isBelowDirt && isRightDirt) return 18;

        // Default to the center grass tile if no edges are adjacent to dirt
        return this.getRandomGrassCenterTile();
    }

    isDirtTile(tileIndex) {
        const dirtTiles = [6, 7, 14, 15, 22, 23]; // indeksi za dirt
        return dirtTiles.includes(tileIndex);
    }

    isGrassCornerTile(tileIndex) {
        const grassCornerTiles = [0, 2, 16, 18]; // indeksi za grass cornere
        return grassCornerTiles.includes(tileIndex);
    }

    isDarkGrassTile(tileIndex) {
        const darkGrassTiles = [24, 25, 26, 33, 34, 35, 42, 43, 44]; // Indices for dark grass tiles
        return darkGrassTiles.includes(tileIndex);
    }

    isDarkGrass(x, y) {
        return Math.random() < 0.1; // 10% chance for dark grass
    }

    getDarkGrassEdgeTile(x, y, terrainTiles) {
        const neighbors = [
            { dx: -1, dy: -1, darkGrassTile: 24 },
            { dx: 0, dy: -1, darkGrassTile: 25 },
            { dx: 1, dy: -1, darkGrassTile: 26 },
            { dx: -1, dy: 0, darkGrassTile: 33 },
            { dx: 1, dy: 0, darkGrassTile: 35 },
            { dx: -1, dy: 1, darkGrassTile: 42 },
            { dx: 0, dy: 1, darkGrassTile: 43 },
            { dx: 1, dy: 1, darkGrassTile: 44 }
        ];

        for (let i = 0; i < neighbors.length; i++) {
            const nx = x + neighbors[i].dx;
            const ny = y + neighbors[i].dy;

            if (nx >= 0 && nx < terrainTiles[0].length && ny >= 0 && ny < terrainTiles.length) {
                if (this.isDirtTile(terrainTiles[ny][nx]) || this.isGrassTile(terrainTiles[ny][nx])) {
                    return neighbors[i].darkGrassTile;
                }
            }
        }
        return getRandomDarkGrassCenterTile();
    }

    hasNeighbouringNonHill(x, y, terrain) {
        let directions = [
            [-1, -1], [0, -1], [1, -1],
            [-1, 0], [1, 0],
            [-1, 1], [0, 1], [1, 1]
        ];

        for (let i = 0; i < directions.length; i++) {
            let dir = directions[i];
            let newX = x + dir[0];
            let newY = y + dir[1];

            if (newX >= 0 && newY >= 0 && newX < terrain[0].length && newY < terrain.length) {
                if (terrain[newY][newX] !== 'H') {
                    return true;
                }
            }
        }

        return false;
    }

    getHillEdgeTile(x, y, terrain) {
        let topLeft = (terrain[y - 1] && terrain[y - 1][x - 1]) === 'H';
        let top = (terrain[y - 1] && terrain[y - 1][x]) === 'H';
        let topRight = (terrain[y - 1] && terrain[y - 1][x + 1]) === 'H';
        let left = (terrain[y][x - 1]) === 'H';
        let right = (terrain[y][x + 1]) === 'H';
        let bottomLeft = (terrain[y + 1] && terrain[y + 1][x - 1]) === 'H';
        let bottom = (terrain[y + 1] && terrain[y + 1][x]) === 'H';
        let bottomRight = (terrain[y + 1] && terrain[y + 1][x + 1]) === 'H';

        if (top && left && !topLeft) return 27; // Top Left Corner
        if (top && right && !topRight) return 29; // Top Right Corner
        if (bottom && left && !bottomLeft) return 45; // Bottom Left Corner
        if (bottom && right && !bottomRight) return 47; // Bottom Right Corner

        if (top && bottom) {
            if (left) return 36; // Middle Left
            if (right) return 38; // Middle Right
        }

        if (left && right) {
            if (top) return 28; // Top Middle
            if (bottom) return 46; // Bottom Middle
        }

        return 37; // Default to the center tile
    }


    generateTerrain(width, height) {
        let terrain = [];
        let overlay = [];

        let noiseScale = -0.05;

        // Initialize terrain and overlay arrays
        for (let y = 0; y < height; y++) {
            let row = [];
            let overlayRow = [];
            for (let x = 0; x < width; x++) {
                let noiseValue = noise.perlin2(x * noiseScale, y * noiseScale);
                let normalizedNoiseValue = (noiseValue + 1) / 2;

                if (normalizedNoiseValue < 0.4) {
                    row.push(this.getRandomDirtTile());
                } else {
                    row.push(this.getRandomGrassCenterTile());
                }
                overlayRow.push(null);
            }
            terrain.push(row);
            overlay.push(overlayRow);
        }

        // Randomly add hills inside the grass chunks
        for (let y = 1; y < height - 1; y++) {
            for (let x = 1; x < width - 1; x++) {
                if (this.isGrassTile(terrain[y][x]) && Math.random() < 0.1) { // 10% chance to start a hill
                    // Add the hill's "H" markers to overlay array
                    overlay[y][x] = 'H';
                    if (this.isGrassTile(terrain[y - 1][x])) overlay[y - 1][x] = 'H';
                    if (this.isGrassTile(terrain[y + 1][x])) overlay[y + 1][x] = 'H';
                    if (this.isGrassTile(terrain[y][x - 1])) overlay[y][x - 1] = 'H';
                    if (this.isGrassTile(terrain[y][x + 1])) overlay[y][x + 1] = 'H';
                }
            }
        }

        // Edge detection and setting appropriate corner/edge tiles for grass
        for (let y = 0; y < height; y++) {
            for (let x = 0; x < width; x++) {
                if (this.isGrassTile(terrain[y][x]) && this.hasNeighbouringDirt(x, y, terrain)) {
                    terrain[y][x] = this.getGrassEdgeTile(x, y, terrain);
                }
            }
        }

        // Edge detection and setting appropriate corner/edge tiles for hills in overlay
        for (let y = 0; y < height; y++) {
            for (let x = 0; x < width; x++) {
                if (overlay[y][x] === 'H' && this.hasNeighbouringNonHill(x, y, overlay)) {
                    overlay[y][x] = this.getHillEdgeTile(x, y, overlay);
                }
            }
        }


        return { terrain, overlay };
    }
}
