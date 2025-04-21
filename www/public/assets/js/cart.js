document.addEventListener('DOMContentLoaded', async function () {
    let userMoney = 0;
    const response = await fetch('/cart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: "list"
        })
    });

    if (!response.ok) {
        throw new Error('Error al obtenir els productes del carret.');
    }

    const data = await response.json();
    const cartItems = data.cart;
    userMoney = data.user_money;
    let total_price = data.total_price;

    const productsTableBody = document.querySelector('#products-list tbody');
    const userMoneyDisplay = document.getElementById("userMoney");
    const cartTotalDisplay = document.getElementById("cartTotal");
    const compraButton = document.getElementById("compraButton");
    const addMoney = document.getElementById("addMoney");

    function updateMoneyDisplay() {
        if (userMoneyDisplay) {
            userMoneyDisplay.textContent = `Balanç: $${userMoney}`;
        }
    }
    function updateCartTotal() {
        cartTotalDisplay.textContent = `Total: $${total_price}`;
    }

    updateMoneyDisplay();
    updateCartTotal();

    productsTableBody.innerHTML = '';

    cartItems.forEach(item => {
        const row = document.createElement('tr');

        row.innerHTML = `
        <td>
            <div class="d-flex">
                <img class="w-10 ms-3" src="${item.product_image}" alt="${item.product_name}">
                <h6 class="ms-3 my-auto">${item.product_name}</h6>
            </div>
        </td>
        <td class="text-sm my-auto">${item.product_category}</td>
        <td class="text-sm my-auto">$<span class="price">${item.product_price}</span></td>
        <td class="text-sm my-auto">$<span class="price">${item.product_total}</span></td>
        <td class="text-sm my-auto">
            <span class="quantity">${item.product_quantity}</span>
        </td>
        <td class="text-sm">
            <button class="btn btn-outline-secondary delete-item" type="button">🗑️</button>
        </td>
    `;

        productsTableBody.appendChild(row);

        const deleteButton = row.querySelector('.delete-item');
        deleteButton.addEventListener('click', async () => {
            row.remove();
            try {
                const response = await fetch('/cart', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        action: "remove",
                        product_name: item.product_name,
                    })
                });

                const result = await response.json();
                total_price = result.total_price;
                updateCartTotal();

                if (response.ok) {

                } else {
                    alert('Error al eliminar el producte.');
                }
            } catch (error) {
                console.error('Error fent la petició:', error);
            }
        });
    });

    compraButton.addEventListener("click", async function () {
        try {
            const response = await fetch('/cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({action: "purchase"})
            });

            if (!response.ok) {
                throw new Error('Error realitzant la compra.');
            }

            const result = await response.json();
            total_price = result.total_price;
            userMoney = result.money;
            if (result.success) {
                alert("Compra feta amb èxit!");
                productsTableBody.innerHTML = '';
                updateMoneyDisplay();
                updateCartTotal();
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.error('Error realitzant la compra:', error);
        }
    })

    addMoney.addEventListener("click", async function(){
        try {
            const response = await fetch('/cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({action: "addMoney"})
            });

            if (!response.ok) {
                throw new Error('Error afegint els diners.');
            }

            const result = await response.json();
            if (result.success) {
                userMoney = result.money;
                updateMoneyDisplay();
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.error('Error realitzant la compra:', error);
        }
    })

});
