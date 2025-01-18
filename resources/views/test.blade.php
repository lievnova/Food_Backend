<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Details</title>
    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    />
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        defer
    ></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Food Details</h1>
        <div class="text-center my-3">
            <button id="fetchFoods" class="btn btn-primary">Load Foods</button>
        </div>
        <div class="row">
            <div id="loading" class="text-center my-3" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div id="error" class="alert alert-danger" style="display: none;"></div>
            <div id="food-container" class="row"></div>
        </div>
    </div>

    <script>
        document.getElementById("fetchFoods").addEventListener("click", () => {
            const apiUrl = "http://127.0.0.1:8000/api/foods"; // Update your API URL
            const foodContainer = document.getElementById("food-container");
            const loading = document.getElementById("loading");
            const errorDiv = document.getElementById("error");

            // Clear previous data or errors
            foodContainer.innerHTML = "";
            errorDiv.style.display = "none";

            // Show loading spinner
            loading.style.display = "block";

            // Fetch data from API
            fetch(apiUrl)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Failed to fetch data");
                    }
                    return response.json();
                })
                .then((foods) => {
                    loading.style.display = "none"; // Hide loading spinner
                    if (foods.length === 0) {
                        foodContainer.innerHTML =
                            "<p class='text-center'>No foods available.</p>";
                        return;
                    }
                    foods.forEach((food) => {
                        const ingredientList = food.ingredients
                            .map((ingredient) => `<li>${ingredient.ingredient_name}</li>`)
                            .join("");

                            const foodCard = `
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <img src="${food.food_image}" alt="${food.name_food}" class="card-img-top img-fluid rounded" style="max-height: 200px; object-fit: cover;" />
                                    <div class="card-body">
                                        <h5 class="card-title">${food.name_food}</h5>
                                        <p><strong>Type:</strong> ${food.type_foods.type_name}</p>
                                        <p><strong>Country:</strong> ${food.country.country_name}</p>
                                        <p><strong>Description:</strong> ${food.description}</p>
                                        <p><strong>How to Make:</strong> ${food.how}</p>
                                        <p><strong>Ingredients:</strong></p>
                                        <ul>${ingredientList}</ul>
                                    </div>
                                </div>
                            </div>`;

                        foodContainer.innerHTML += foodCard;
                    });
                })
                .catch((error) => {
                    loading.style.display = "none"; // Hide loading spinner
                    errorDiv.style.display = "block";
                    errorDiv.textContent = `Error: ${error.message}`;
                });
        });
    </script>
</body>
</html>
