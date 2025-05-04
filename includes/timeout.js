let timeout;

function resetTimeout() {
    clearTimeout(timeout); 
    timeout = setTimeout(() => {
        window.location.href = "/SoftwareEngineering2Final/src/logout.php";
    }, 600000); //(milliseconds)
}

// Reset timer if meron ff
window.onload = resetTimeout;
document.onmousemove = resetTimeout;
document.onkeypress = resetTimeout;
document.onscroll = resetTimeout;
