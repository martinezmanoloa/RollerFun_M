# API Usage Guide (Postman / Insomnia)

This guide provides the details for interacting with the `UserController` endpoints. The base URL for all requests is: `http://localhost/api`.

---

## 👥 User Management Endpoints

### 1. List All Users
Retrieve a list of all users stored in the database.

* **Method:** `GET`
* **URL:** `http://localhost/api/show/users`
* **Response:** `200 OK` with a JSON array of users.

### 2. Create a New User
Add a new user to the system. Note that validation is required: Name (min 3 chars) and Age (must be > 5).

* **Method:** `POST`
* **URL:** `http://localhost/api/new/users`
* **Body (JSON):**
    ```json
    {
      "name": "Manolo Martinez",
      "age": 35
    }
    ```
* **Response:** `201 Created` on success.

### 3. Get User by ID
Fetch the details of a specific user using their unique ID.

* **Method:** `GET`
* **URL:** `http://localhost/api/show/users/{id}`
* **Example:** `http://localhost/api/show/users/1`
* **Response:** `200 OK` or `404 Not Found` if the ID does not exist.

### 4. Update User
Modify an existing user's information. You can send only the fields you wish to change.

* **Method:** `PUT`
* **URL:** `http://localhost/api/edit/users/{id}`
* **Body (JSON):**
    ```json
    {
      "name": "Manolo A. Martinez",
      "age": 36
    }
    ```
* **Response:** `200 OK` with the updated user data.

### 5. Delete User
Remove a user from the database permanently.

* **Method:** `DELETE`
* **URL:** `http://localhost/api/delete/users/{id}`
* **Response:** `200 OK` with a success message.

---

## 🛠️ Common Troubleshooting

* **Invalid JSON:** Ensure your request header includes `Content-Type: application/json`.
* **Validation Errors:** If you receive a `400 Bad Request`, check the `details` field in the response to see which validation rule (name length or age limit) failed.
* **Database Connection:** Ensure you have run the migrations (`php bin/console doctrine:migrations:migrate`) before testing these endpoints.
