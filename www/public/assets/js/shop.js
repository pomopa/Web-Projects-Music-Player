document.addEventListener("DOMContentLoaded", function () {
    let products = [];

    const searchInput = document.getElementById("searchInput");
    const searchButton = document.getElementById("searchButton");
    const productContainer = document.getElementById("productContainer");
    const categoryButton = document.getElementById("categoryFilterButton");
    const categoryItems = document.querySelectorAll(".dropdown-item");

    async function fetchProducts() {
        try {
            const response = await fetch("/search", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({})
            });
            const data = await response.json();
            products = data.products.slice(0, 20);
            displayProducts(products);
        } catch (error) {
            console.error("Error llegint els productes:", error);
        }
    }

    async function fetchProductsCategory(category) {
        try {
            const response = await fetch("/search", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ category })
            });
            const data = await response.json();
            products = data.products.slice(0, 20);
            displayProducts(products);
        } catch (error) {
            console.error("Error llegint els productes:", error);
        }
    }

    async function fetchProductsSearch(query) {
        try {
            const response = await fetch("/search", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ q: query })
            });
            const data = await response.json();
            products = data.products.slice(0, 20);
            displayProducts(products);
        } catch (error) {
            console.error("Error llegint els productes:", error);
        }
    }

    function displayProducts(productList) {
        productContainer.innerHTML = "";
        if (productList.length === 0) {
            productContainer.innerHTML = "<h6 class='text-center w-100 text-white'>Cap producte trobat.</h6>";
            return;
        }

        productList.forEach(product => {
            const card = `
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header p-0 position-relative d-block mx-auto">
                            <img src="${product.thumbnail}" alt="${product.title}" class="img-fluid">
                        </div>
                        <div class="card-body text-center">
                            <h5>${product.title}</h5>
                            <p>${product.description}</p>
                            <p><strong>Marca:</strong> ${product.brand}</p>
                            <p><strong>Categoria:</strong> ${product.category}</p>
                            <p><strong>Preu:</strong> $${product.price}</p>
                            <button class="btn bg-gradient-dark shadow-success add-to-cart" data-id="${product.id}">Afegeix al carret</button>
                        </div>
                    </div>
                </div>`;
            productContainer.innerHTML += card;
        });

        document.querySelectorAll(".add-to-cart").forEach(button => {
            button.addEventListener("click", async function () {
                try {
                    const productId = this.dataset.id;
                    const response = await fetch("/search/addToCart", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({ product_id: productId })
                    });

                    const data = await response.json();

                    if (data.status === "error") {
                        alert(data["message"]);
                        window.location.href = "/sign-in";
                    } else {
                        alert(data["message"]);
                    }
                } catch (error) {
                    console.error("Error afegint el producte al carret: ", error);
                }
            });
        });
    }

    searchInput.addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
            const query = searchInput.value.toLowerCase();
            fetchProductsSearch(query);
            categoryButton.textContent = "Tots";
        }
    });

    searchButton.addEventListener("click", function () {
        const query = searchInput.value.toLowerCase();
        fetchProductsSearch(query);
        categoryButton.textContent = "Tots";
    });

    categoryItems.forEach(item => {
        item.addEventListener("click", function () {
            const selectedCategory = item.getAttribute("data-category");
            categoryButton.textContent = item.textContent.trim();
            if (selectedCategory === "all") {
                fetchProducts();
            } else {
                fetchProductsCategory(selectedCategory);
            }
        });
    });
    fetchProducts();
});
