// parallax effect on main menu 
document.addEventListener("mousemove", parallax);
function parallax(e) {
    let background = document.getElementById('background');
    let scene = document.getElementById('scene');
    let rect = scene.getBoundingClientRect();
    let relX = e.clientX - rect.left - scene.offsetWidth / 2;
    let relY = e.clientY - rect.top - scene.offsetHeight / 2;

    scene.style.transform = "translate(" + (-relX / 50) + "px, " + (-relY / 50) + "px)";
    background.style.transform = "translate(" + (-relX / 100) + "px, " + (-relY / 100) + "px)";
}


