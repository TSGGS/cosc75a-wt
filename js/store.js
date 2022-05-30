function addtoCart(prod) {
    // alert(prod);
    let counter = document.getElementById("cart-count");
    let product = {
        "type": "cart",
        "product": prod
    };

    let xhr = new XMLHttpRequest();
    let jsonString = JSON.stringify(product);
    let url = "includes/receive.php";

    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.responseType = "json";
    xhr.onload = function (){
        let response = xhr.response;
        counter.innerHTML = response["count"];
        
    };

    xhr.send(jsonString);
}