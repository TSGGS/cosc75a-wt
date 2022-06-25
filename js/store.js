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

function deleteItem(code) {
    let itemCount = document.getElementById("cart-count");
    let element = document.getElementById(code+"-row");

    delete list[code];
    updateTotal();
    removeTotal();

    let product = {
        "type": "delete",
        "product": code
    };

    let xhr = new XMLHttpRequest();
    let jsonString = JSON.stringify(product);
    let url = "includes/receive.php";

    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.send(jsonString);

    itemCount.innerText = itemCount.innerText - 1;
    element.parentNode.removeChild(element);
}

function removeTotal() {
    if(Object.keys(list).length  == 0) {
        let area = document.getElementById("cart-item-area");
        let row = document.getElementById("grandTotalRow");

        row.parentNode.removeChild(row);

        let noItem = document.createElement("div");
        let noItemContent = document.createElement("div");

        noItem.classList.add("row", "mt-3");
        noItemContent.classList.add("col", "text-center");
        noItemContent.innerHTML = "There is no product added to cart";

        noItem.appendChild(noItemContent);
        area.appendChild(noItem);
    } 
}