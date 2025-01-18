<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Food</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 py-10 px-5">
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded p-6">
        <h2 class="text-2xl font-bold mb-4">Create Food</h2>
        <form id="foodForm" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="name_food" class="block text-sm font-medium">Food Name</label>
                <input type="text" id="name_food" name="name_food" required class="mt-1 block w-full border rounded-md p-2">
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium">Description</label>
                <textarea id="description" name="description" class="mt-1 block w-full border rounded-md p-2"></textarea>
            </div>

            <div class="mb-4">
                <label for="how_make" class="block text-sm font-medium">How to Make</label>
                <textarea id="how_make" name="how_make" class="mt-1 block w-full border rounded-md p-2"></textarea>
            </div>

            <div class="mb-4">
                <label for="dis" class="block text-sm font-medium">Notes</label>
                <textarea id="dis" name="dis" class="mt-1 block w-full border rounded-md p-2"></textarea>
            </div>

            <div class="mb-4">
                <label for="photo" class="block text-sm font-medium">Photo</label>
                <input type="file" id="photo" name="photo" accept="image/*" required class="mt-1 block w-full border rounded-md p-2">
            </div>

            <div class="mb-4">
                <label for="country_id" class="block text-sm font-medium">Country</label>
                <select id="country_id" name="country_id" class="mt-1 block w-full border rounded-md p-2">
                    <option value="">Select a country</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="type_id" class="block text-sm font-medium">Food Type</label>
                <select id="type_id" name="type_id" class="mt-1 block w-full border rounded-md p-2">
                    <option value="">Select a food type</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="ingredients" class="block text-sm font-medium">Ingredients</label>
                <select id="ingredients" name="ingredients[]" multiple class="mt-1 block w-full border rounded-md p-2">
                    <option value="">Select ingredients</option>
                </select>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Submit</button>
        </form>

        <div id="foodDetails" class="mt-8 hidden">
            <h3 class="text-xl font-bold mb-4">Created Food Details</h3>
            <p><strong>Name:</strong> <span id="foodName"></span></p>
            <p><strong>Description:</strong> <span id="foodDescription"></span></p>
            <p><strong>How to Make:</strong> <span id="foodHowMake"></span></p>
            <p><strong>Country:</strong> <span id="foodCountry"></span></p>
            <p><strong>Type:</strong> <span id="foodType"></span></p>
            <p><strong>Ingredients:</strong></p>
            <ul id="foodIngredients" class="list-disc pl-5"></ul>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const countrySelect = document.getElementById('country_id');
            const typeSelect = document.getElementById('type_id');
            const ingredientsSelect = document.getElementById('ingredients');
            
            // Fetch initial data for dropdowns
            axios.get('http://127.0.0.1:8000/api/countrys').then(response => {
                response.data.forEach(country => {
                    const option = document.createElement('option');
                    option.value = country.country_id;
                    option.textContent = country.country_name;
                    countrySelect.appendChild(option);
                });
            });

            axios.get('http://127.0.0.1:8000/api/foods/typefoods').then(response => {
                console.log(response.data); // Check the full response structure
                response.data.forEach(food_type => {
                    console.log(food_type.type_name); // Log each food_type to verify the structure
                    const option = document.createElement('option');
                    option.value = food_type.type_id;
                    option.textContent = food_type.type_name;
                    typeSelect.appendChild(option);
                });
            });



            axios.get('http://127.0.0.1:8000/api/ingredients').then(response => {
                response.data.forEach(ingredient => {
                    const option = document.createElement('option');
                    option.value = ingredient.ingredient_id;
                    option.textContent = ingredient.ingredient_name;
                    ingredientsSelect.appendChild(option);
                });
            });

            // Form submission
            document.getElementById('foodForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);

                axios.post('http://127.0.0.1:8000/api/foods/store', formData)
                    .then(response => {
                        const food = response.data.food;
                        document.getElementById('foodDetails').classList.remove('hidden');
                        document.getElementById('foodName').textContent = food.name_food;
                        document.getElementById('foodDescription').textContent = food.description || 'N/A';
                        document.getElementById('foodHowMake').textContent = food.how_make || 'N/A';
                        document.getElementById('foodCountry').textContent = food.country.country_name || 'N/A';
                        document.getElementById('foodType').textContent = food.typefood.type_name || 'N/A';
                        const ingredientsList = document.getElementById('foodIngredients');
                        ingredientsList.innerHTML = '';
                        food.combined_ingredients.forEach(ci => {
                            const li = document.createElement('li');
                            li.textContent = ci.ingredient.ingredient_name;
                            ingredientsList.appendChild(li);
                        });
                    })
                    .catch(error => {
                        alert('Error creating food');
                        console.error(error);
                    });
            });
        });
    </script>
</body>
</html>
