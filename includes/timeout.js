let timeout;

function resetTimeout() {
    clearTimeout(timeout); 
    timeout = setTimeout(() => {
        window.location.href = "../login/logout.php"; // Use relative path
    }, 600000); //(milliseconds)
}

// Reset timer if meron ff
window.onload = resetTimeout;
document.onmousemove = resetTimeout;
document.onkeypress = resetTimeout;
document.onscroll = resetTimeout;
