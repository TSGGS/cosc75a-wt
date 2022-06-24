function addtoCart(prod) {
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

let list = [];
function updateQty(code, price) {
    let qty = document.getElementById(code+"-qty");
    let display = document.getElementById(code+"-total");

    if(qty.value === "") {
        qty.innerText = 1;
        qty.value = 1;
    }

    list[code]["count"] = qty.value;

    let total = qty.value * price;

    display.innerHTML = "₱ "+total;
    updateTotal();
}

function initTotal(cartList) {
    cartList.forEach(item => {
        callPrice(item);
        list[item] = {
            "price": 0,
            "count": 1
        };
    });
}

function callPrice(item) {
    let product = {
        "type": "price",
        "product": item
    };

    let xhr = new XMLHttpRequest();
    let jsonString = JSON.stringify(product);
    let url = "includes/receive.php";

    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.responseType = "json";
    xhr.onload = function (){
        let response = xhr.response;
        for (i in list) {
            if(i == response["product"]) {
                list[i]["price"] = response["price"];
            }
        }
        updateTotal();
    };

    xhr.send(jsonString);
}

function updateTotal() {
    let displayTotal = document.getElementById("grandTotalDisplay");
    let grandTotal = 0;
    for (i in list) {
        let itemTotal = 0;
        itemTotal = list[i]["count"] * list[i]["price"];
        grandTotal += itemTotal;
    }

    displayTotal.innerHTML = "₱ "+grandTotal;
}