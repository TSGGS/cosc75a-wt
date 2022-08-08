function displayUpdateData(product_code, product_name, product_type, product_image, price_amount, product_description) {
    let pCode = document.getElementById("update-product-code");
    let pName = document.getElementById("update-product-name");
    let pType = document.getElementById("update-product-type");
    let pImage = document.getElementById("update-product-image-display");
    let prAmount = document.getElementById("update-product-price");
    let pDescription = document.getElementById("update-product-desc");

    pCode.value = product_code;
    pName.value = product_name;
    pType.value = product_type;

    pImage.src = "../../images/product_images/"+product_image;
    document.getElementById("update-product-image-display-div").classList.remove("d-none");

    prAmount.value = price_amount;
    pDescription.value = product_description;
}

function displayUpdatePromotion(promotion_code, promotion_image, promotion_start, promotion_end) {
    let prCode = document.getElementById("update-promotion-code");
    let prImage = document.getElementById("update-promotion-image-display");
    let prStart = document.getElementById("update-promotion-start");
    let prEnd = document.getElementById("update-promotion-end");

    prCode.value = promotion_code;
    prStart.value = promotion_start;
    prEnd.value = promotion_end;

    prImage.src = "../../images/promotions/"+promotion_image;
    document.getElementById("update-promotion-image-display-div").classList.remove("d-none");

}

function toggleError(element_id, action) {
    if(action === "show") {
        document.getElementById(element_id).classList.remove("d-none");
    } else {
        document.getElementById(element_id).classList.add("d-none");
    }
}

function viewOrder(id, name, mobile, address, price, status, datetime, statusName, empName) {
    let modal = document.getElementById("modal");
    let orderFull = document.getElementById("orderFull");

    let orderID = document.getElementById("orderID");
    let orderName = document.getElementById("orderName");
    let orderAddress = document.getElementById("orderAddress");
    let orderContact = document.getElementById("orderContact");
    let orderPrice = document.getElementById("orderPrice");
    let orderDelivery = document.getElementById("orderDelivery");
    let orderList = document.getElementById("orderList");
    let orderStatus = document.getElementById("orderStatus");
    let orderEmployee = document.getElementById("orderEmployee");

    let handler = document.getElementById("handler");
    let handler1 = document.getElementById("handler1");
    let noHandler = document.getElementById("noHandler");

    let btn_accept = document.getElementById("btn-accept");
    let btn_decline = document.getElementById("btn-decline");

    let product = {
        "type": "orderList",
        "id": id
    };

    let xhr = new XMLHttpRequest();
    let jsonString = JSON.stringify(product);
    let url = "../../includes/receive.php";

    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.responseType = "json";
    xhr.onload = function (){
        let response = xhr.response;
        orderList.innerHTML = response["orders"];

        orderID.innerText = "Order #"+id;
        orderName.innerText = name;
        orderAddress.innerText = address;
        orderContact.innerText = mobile;
        orderPrice.innerText = "₱ "+price;
        orderDelivery.innerText = datetime;
        orderStatus.innerText = statusName;

        if(status != 1) {
            orderEmployee.innerText = empName;

            handler.classList.remove("d-none");
            handler1.classList.remove("d-none");
            noHandler.classList.add("d-none");
        } else {
            handler.classList.add("d-none");
            noHandler.classList.remove("d-none");
        }

        btn_accept.onclick = function() {
            confirmStatus('accept', id)
        };
        btn_decline.onclick = function() {
            confirmStatus('decline', id)
        };

        modal.classList.remove("d-none");
        orderFull.classList.remove("d-none");
        orderFull.scrollTo(0,0);
    };

    xhr.send(jsonString);
}

function removeModal() {
    let modal = document.getElementById("modal");
    let orderFull = document.getElementById("orderFull");
    orderFull.classList.add("d-none");
    modal.classList.add("d-none");
}

function confirmStatus(choice, id) {
    let packet = {
        "type": "orderConfirmation",
        "choice": choice,
        "id": id
    };

    let xhr = new XMLHttpRequest();
    let jsonString = JSON.stringify(packet);
    let url = "../../includes/receive.php";

    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.responseType = "json";
    xhr.onload = function (){
        window.location.reload();
    };

    xhr.send(jsonString);
}