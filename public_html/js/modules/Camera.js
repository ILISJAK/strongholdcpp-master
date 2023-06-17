export default class Camera {
    constructor(gameContentElement) {
        this.gameContentElement = gameContentElement;
        this.isPanning = false;
        this.initialX = null;
        this.initialY = null;
        this.offsetX = 0;
        this.offsetY = 0;
        this.zoomScale = 1;

        this.gameContentElement.addEventListener("mousedown", (event) => this.startPan(event));
        this.gameContentElement.addEventListener("mousemove", (event) => this.pan(event));
        this.gameContentElement.addEventListener("mouseup", () => this.stopPan());
        this.gameContentElement.addEventListener("wheel", (event) => this.handleZoom(event), { passive: false });
    }

    startPan(event) {
        this.isPanning = true;
        this.initialX = event.clientX;
        this.initialY = event.clientY;
        this.gameContentElement.style.cursor = "grabbing";
    }

    pan(event) {
        if (this.isPanning) {
            let dx = event.clientX - this.initialX;
            let dy = event.clientY - this.initialY;
            this.offsetX += dx;
            this.offsetY += dy;
            this.initialX = event.clientX;
            this.initialY = event.clientY;
            this.updateTransform();
        }
    }

    stopPan() {
        this.isPanning = false;
        this.gameContentElement.style.cursor = "grab";
    }

    handleZoom(event) {
        event.preventDefault();
        let delta = event.deltaY || event.detail || event.wheelDelta; 
        let zoomStep = 0.1;
        let zoomMin = 0.25;
        let zoomMax = 2;
        let previousZoomScale = this.zoomScale;

        if (delta > 0) {
            this.zoomScale = Math.max(zoomMin, this.zoomScale - zoomStep);
        } else {
            this.zoomScale = Math.min(zoomMax, this.zoomScale + zoomStep);
        }

        // Calculate the cursor position relative to the game content
        let rect = this.gameContentElement.getBoundingClientRect();
        let cursorX = event.clientX - rect.left;
        let cursorY = event.clientY - rect.top;

        // Calculate the position of the cursor relative to the game content, normalized by the current scale
        let normalizedX = (cursorX - this.offsetX) / previousZoomScale;
        let normalizedY = (cursorY - this.offsetY) / previousZoomScale;

        // Adjust the offsets
        this.offsetX = cursorX - normalizedX * this.zoomScale;
        this.offsetY = cursorY - normalizedY * this.zoomScale;

        // Apply the new zoom scale and offsets
        this.updateTransform();
    }

    updateTransform() {
        this.gameContentElement.style.transform = `translate(${this.offsetX}px, ${this.offsetY}px) scale(${this.zoomScale})`;
    }
}
