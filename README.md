# StrongholdCPP

StrongholdCPP is a robust turn-based strategy game powered by JavaScript and PHP for frontend and C++ for the backend. This project leverages the capabilities of the `StrongholdCPP API` to manage game instances, handle move actions, fetch game properties, save and load a game.

## Table of Contents

- [Features](#features)
- [Dependencies](#dependencies)
- [Installation](#installation)
- [Usage](#usage)

## Features

1. **Intricate Turn-Based Gameplay:** Engage in strategic planning and tactical execution in a comprehensive gameplay framework.

2. **Dynamic Visuals:** Experience the game through a visually compelling interface developed in JavaScript and PHP.

3. **Procedurally generated terrain** The game terrain is completely procedural using perlin noise through [noise.js](https://github.com/josephg/noisejs).

4. **Robust Backend:** Benefit from a solid C++ backend managed by the StrongholdCPP API, handling all game logic and operations.

## Dependencies

StrongholdCPP game project requires:

- The frontend requires a modern web browser that supports JavaScript and PHP.
- The backend uses the [StrongholdCPP API](https://github.com/ILISJAK/strongholdcpp-api.git).
- PHP requires a web server to run on, preferably apache2.
- MySQL server for the database
  
## Installation

### Prerequisites

Ensure you have installed and set up the StrongholdCPP API as detailed in its [repository](https://github.com/ILISJAK/strongholdcpp-api.git). 

### Installation Steps

1. Clone the repository:

   ```shell
   git clone https://github.com/YourUsername/strongholdcpp-master.git
   ```

2. Navigate to the project directory:

   ```shell
   cd strongholdcpp-master
   ```

3. Follow the instructions in the `Installation` section of the StrongholdCPP API README to install and set up the backend.

4. With the StrongholdCPP API server running, host an apache2 server and open the index PHP file in a web server that supports PHP to start the game.

## Usage

StrongholdCPP is a web-based game, and you should access it via a web browser. To play the game, simply navigate to the PHP file in your web server.

## Acknowledgements

This project uses the `noise.js` library, Copyright (c) 2013, Joseph Gentle.
