
var modal = document.getElementById("editModal");


function openModal() {
    modal.style.display = "block";
}


function closeModal() {
    modal.style.display = "none";
}


window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}


function test(){
    alert("hi")
}


