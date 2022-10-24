function destroyPromotion() {
    let modal = document.getElementById("modal");
    let promotion = document.getElementById("promotion");

    promotion.parentNode.removeChild(promotion);
    modal.parentNode.removeChild(modal);
    document.body.style.overflowY = "scroll";
}

function addtoCart(prod) {
    let counter = document.getElementById("cart-count");
    let product = {
        "type": "cartAdd",
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

function updateQty(code, price, max) {
    let qty = document.getElementById(code+"-qty");
    let display = document.getElementById(code+"-total");

    if(qty.value === "" || qty.value < 0) {
        qty.innerText = 1;
        qty.value = 1;
    } else if(qty.value > max) {
        qty.innerText = max;
        qty.value = max;
    }
     else if(qty.value == "0") {
        deleteItem(code);
    }

    let data = {
        "type": "updateQty",
        "product": code,
        "quantity": qty.value
    };

    let xhr = new XMLHttpRequest();
    let jsonString = JSON.stringify(data);
    let url = "includes/receive.php";

    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.send(jsonString);

    let total = qty.value * price;

    display.innerHTML = "₱ "+total;
    updateTotal();
}

function updateTotal() {
    let displayTotal = document.getElementById("grandTotalDisplay");

    let data = {
        "type": "updateTotal"
    };

    let xhr = new XMLHttpRequest();
    let jsonString = JSON.stringify(data);
    let url = "includes/receive.php";

    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.responseType = "json";
    xhr.onload = function (){
        let response = xhr.response;
        displayTotal.innerHTML = "₱ "+response["grandTotal"];
    };

    xhr.send(jsonString);
}

function deleteItem(code) {
    let itemCount = document.getElementById("cart-count");
    let element = document.getElementById(code+"-row");

    let data = {
        "type": "delete",
        "product": code
    };

    let xhr = new XMLHttpRequest();
    let jsonString = JSON.stringify(data);
    let url = "includes/receive.php";

    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.responseType = "json";
    xhr.onload = function (){
        let response = xhr.response;
        updateTotal();
        removeTotal(response["count"]);
        itemCount.innerText = response["count"];
    };

    xhr.send(jsonString);

    element.parentNode.removeChild(element);
}

function removeTotal(count) {
    if(count == 0) {
        let area = document.getElementById("cart-item-area");
        let row = document.getElementById("grandTotalRow");
        let discount = document.getElementById("discountRow");
        let button = document.getElementById("orderButtonRow");

        row.parentNode.removeChild(row);
        discount.parentNode.removeChild(discount);
        button.parentNode.removeChild(button);

        let noItem = document.createElement("div");
        let noItemContent = document.createElement("div");

        noItem.classList.add("row", "mt-3");
        noItemContent.classList.add("col", "text-center");
        noItemContent.innerHTML = "There is no product added to cart";

        noItem.appendChild(noItemContent);
        area.appendChild(noItem);
    } 
}

function applyDiscount() {
    let applyBtn = document.getElementById("apply-discount");
    let removeBtn = document.getElementById("remove-discount");
    let codeInputBox = document.getElementById("apply-discount-code");
    let code = codeInputBox.value;

    if(code != "") {
        let data = {
            "type": "discountApply",
            "code": code.toUpperCase()
        };
    
        let xhr = new XMLHttpRequest();
        let jsonString = JSON.stringify(data);
        let url = "includes/receive.php";
    
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/json");
    
        xhr.responseType = "json";
        xhr.onload = function (){
            let response = xhr.response;

            if(response["status"] === "VALID") {
                updateTotal();
                codeInputBox.readOnly = true;
                applyBtn.style.display = "none";
                removeBtn.style.display = "";
            }
        };
    
        xhr.send(jsonString);
    }
}

function removeDiscount() {
    let applyBtn = document.getElementById("apply-discount");
    let removeBtn = document.getElementById("remove-discount");
    let codeInputBox = document.getElementById("apply-discount-code");
    let code = codeInputBox.value;

    let packet = {
        "type": "discountRemove"
    };

    let xhr = new XMLHttpRequest();
    let jsonString = JSON.stringify(packet);
    let url = "includes/receive.php";

    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.send(jsonString);

    updateTotal();
    removeBtn.style.display = "none";
    applyBtn.style.display = "";
    codeInputBox.readOnly = false;
    codeInputBox.value = "";
    
}

function placeOrder() {
    window.location.href = 'customer.php';
}

function checkedCheckbox() {
    let checkBox = document.getElementById("customer-time-asap");
    let date = document.getElementById("customer-date");
    let time = document.getElementById("customer-time");

    if(checkBox.checked == true) {
        let cur = new Date();

        date.value = cur.toISOString().split("T")[0]
        time.value = "";
        date.readOnly = true;
        time.readOnly = true;
    } else {
        date.readOnly= false;
        time.readOnly= false;
    }
}

function checkASAP() {
    let cur = new Date();
    let date = document.getElementById("customer-date");
    let time = document.getElementById("customer-time");
    let checkBox = document.getElementById("customer-time-asap");

    let custDate = new Date(date.value);
    let timeSplit = String(time.value).split(":");
    custDate.setHours(parseInt(timeSplit[0]), parseInt(timeSplit[1]), 0, 0);

    cur.setMinutes(cur.getMinutes()+10, 0, 0);

    if(custDate.valueOf() < cur.valueOf()) {
        checkBox.checked = true;
        checkedCheckbox();
    }

}

function toggleError(element_id, action) {
    if(action === "show") {
        document.getElementById(element_id).classList.remove("d-none");
    } else {
        document.getElementById(element_id).classList.add("d-none");
    }
}