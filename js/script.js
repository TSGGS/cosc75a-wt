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

function displayUpdateDiscount(discount_code, discount_amount, discount_start, discount_end) {
    let dCode = document.getElementById("update-discount-code");
    let dAmount = document.getElementById("update-discount-amount");
    let dStart = document.getElementById("update-discount-start");
    let dEnd = document.getElementById("update-discount-end");

    dCode.value = discount_code;
    dAmount.value = discount_amount;
    dStart.value = discount_start;
    dEnd.value = discount_end;
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

//#region OPS NEW/UPDATE PRODUCT VALIDATION
async function verifyProductCode(pCode) {
    if(pCode.match(/[A-Z0-9]{3,}/) === null) {
        document.getElementById("new-product-code-error").classList.remove("d-none");
        return false;
    } else {
        let data = {
            "type": "verifyProductCode",
            "pcode": pCode
        };

        const endpoint = new URL("http://localhost:5000/wt_shop/includes/receive.php");
        const response = await fetch(endpoint, {
            method: "POST",
            headers: {
                "Content-type": "application/json"
            },
            body: JSON.stringify(data)
        }).catch(() => {
            alert("There was a communication problem in the server. Please try again.")
        });

        const status = await response.json();

        if(status.status !== "VALID") {
            document.getElementById("new-product-code-error").classList.remove("d-none");
            return false;
        } else {
            document.getElementById("new-product-code-error").classList.add("d-none");
            return true;
        }
    }
}

async function verifyProductCodeUpdate(pCode) {
    if(pCode.match(/[A-Z0-9]{3,}/) === null) {
        document.getElementById("update-product-code-error").classList.remove("d-none");
        return false;
    } else {
        let data = {
            "type": "verifyProductCodeUpdate",
            "pcode": pCode
        };

        const endpoint = new URL("http://localhost:5000/wt_shop/includes/receive.php");
        const response = await fetch(endpoint, {
            method: "POST",
            headers: {
                "Content-type": "application/json"
            },
            body: JSON.stringify(data)
        }).catch(() => {
            alert("There was a communication problem in the server. Please try again.")
        });

        const status = await response.json();

        if(status.status !== "VALID") {
            document.getElementById("update-product-code-error").classList.remove("d-none");
            return false;
        } else {
            document.getElementById("update-product-code-error").classList.add("d-none");
            return true;
        }
    }
}

function verifyProductName(pName, id) {
    let regex = pName.match(/^[^-\s][a-zA-Z0-9 '-]+$/gm);

    if(regex === null) {
        document.getElementById(id).classList.remove("d-none");
        return false;
    } else {
        document.getElementById(id).classList.add("d-none");
        return true;
    }
}

function verifyProductType(pType, id) {
    let value = pType.options[pType.selectedIndex].value
    
    if(value === "--SELECT PRODUCT TYPE--") {
        document.getElementById(id).classList.remove("d-none");
        return false;
    } else {
        document.getElementById(id).classList.add("d-none");
        return true;
    }
}

function verifyProductImge(pImge, id) {
    if(pImge === undefined) {
        document.getElementById(id).classList.remove("d-none");
        return false;
    } else {
        if(!pImge.type.match("image.*")) {
            document.getElementById(id).classList.remove("d-none");
            return false;
        } else {
            document.getElementById(id).classList.add("d-none");
            return true;
        }
    }
}

function verifyProductPrce(pPrce, id) {
    if(pPrce < 1) {
        document.getElementById(id).classList.remove("d-none");
        return false;
    } else {
        document.getElementById(id).classList.add("d-none");
        return true;
    }
}

function verifyProductDesc(pDesc, id) {
    if(pDesc.match(/^[^-\s][a-zA-Z0-9 "'-_]+$/gm) === null) {
        document.getElementById(id).classList.remove("d-none");
        return false;
    } else {
        document.getElementById(id).classList.add("d-none");
        return true;
    }
}

async function verifyNewProduct(e) {
    e.preventDefault();

    let pCode = document.getElementById("new-product-code").value;
    let pName = document.getElementById("new-product-name").value;
    let pType = document.getElementById("new-product-type");
    let pImge = document.getElementById("new-product-image").files[0];
    let pPrce = document.getElementById("new-product-price").value;
    let pDesc = document.getElementById("new-product-desc").value;

    verifyProductCode(pCode).then((status) => {
        let isPCodeValid = status;
        let isPNameValid = verifyProductName(pName, "new-product-name-error");
        let isPTypeValid = verifyProductType(pType, "new-product-type-error");
        let isPImgeValid = verifyProductImge(pImge, "new-product-image-error");
        let isPPrceValid = verifyProductPrce(pPrce, "new-product-price-error");
        let isPDescValid = verifyProductDesc(pDesc, "new-product-desc-error");
    
        if(isPCodeValid && isPNameValid && isPTypeValid && isPImgeValid && isPPrceValid && isPDescValid) {
            document.getElementById("new-product-form").submit();
        }
    });
}

async function verifyUpdateProduct(e) {
    e.preventDefault();

    let pCode = document.getElementById("update-product-code").value;
    let pName = document.getElementById("update-product-name").value;
    let pType = document.getElementById("update-product-type");
    let pImge = document.getElementById("update-product-image").files[0];
    let pPrce = document.getElementById("update-product-price").value;
    let pDesc = document.getElementById("update-product-desc").value;

    verifyProductCodeUpdate(pCode).then((status) => {
        let isPCodeValid = status;
        let isPNameValid = verifyProductName(pName, "update-product-name-error");
        let isPTypeValid = verifyProductType(pType, "update-product-type-error");
        let isPImgeValid = true;
        let isPPrceValid = verifyProductPrce(pPrce, "update-product-price-error");
        let isPDescValid = verifyProductDesc(pDesc, "update-product-desc-error");

        if(pImge !== undefined) {
            isPImgeValid = verifyProductImge(pImge, "update-product-image-error");
        }
    
        if(isPCodeValid && isPNameValid && isPTypeValid && isPImgeValid && isPPrceValid && isPDescValid) {
            document.getElementById("update-product-form").submit();
        }
    });
}
//#endregion

