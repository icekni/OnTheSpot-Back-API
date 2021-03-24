**Table of contents**
===============
* [API] (#api)
    * [Login] (#login)
    * [Registering a user] (#registering)
    * [Deleting your account] (#delete-user)
    * [Browse all categories] (#browse-categories)
    * [Browse all products from a specified category](#browse-products)
    * [Read a specified product](#read-products)
    * [Browse all Delivery Points] (#browse-deliveryPoints)
    * [Send an order] (#post-orders)
    * [Browse all orders from a logged user] (#browse-orders)
    * [Read a specified orders from a logged user] (#read-orders)

**API**
===============

**Login**
----
  Authenticate a user
  
* **Access to**
  
  all

* **URL**

  /login

* **Method:**

  `POST`

* **Data Params**

   **Required:**

  ```json
    {
        "username": "sample@mail.com",
        "password": "password"
    }
  ```

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    ```json
    {
        "token": "eyJ0eXAiOiJ...zQEA"
    }
    ```
 
* **Error Response:**

  * **Code:** 401 UNAUTHORIZED <br />
    **Content:** 
    ```json
    {
        "code": 401,
        "message": "Invalid credentials."
    }
    ```

**Registering**
----
  Register a user
  
* **Access to**
  
  all

* **URL**

  /users

* **Method:**

  `POST`

* **Data Params**

   **Required:**

  ```json
    {
        "firstname": "firstname",
	    "lastname": "lastname",
        "email": "sample@mail.com",
        "roles": ["ROLE_USER"],
        "telNumber": "0000000000",
        "password": "password"
    }
  ```

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    ```json
    {
        "id": <id>,
        "email": "sample@mail.com",
        "username": "sample@mail.com",
        "roles": [
            "ROLE_USER"
        ],
        "password": "$argon2...WQi9p4",
        "salt": null,
        "firstname": "firstname",
        "lastname": "lastname",
        "telNumber": "0000000000",
        "createdAt": "2021...",
        "updatedAt": null,
        ...
    }
    ```
 
* **Error Response:**

  * **Code:** 422 Unprocessable Entity <br />
    **Content:** 
    ```json
    {
        ...
        "violations": [
            {
            "propertyPath": "<property>",
            "title": "<error message>",
            ...
        ]
    }
    ```

**Delete User**
----
  Delete your account 
  
* **Access to**
  
  Logged users

* **URL**

  /users

* **Method:**

  `DELETE`

* **Data Params**

   none

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    ```json
    {
        "id": <id>,
        "email": "sample@mail.com",
        "username": "sample@mail.com",
        ...
    }
    ```
 
* **Error Response:**

  * **Code:** 404 User Not Found <br />
    **Content:** 
    ```json
    {
        "status": 404,
        "message": "User not found"
    }
    ```

**Browse Categories**
----
  Browse all categories
  
* **URL**

  /categories
  
* **Access to**
  
  all

* **Method:**

  `GET`

* **Data Params**

   none

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    ```json
    [
        {
            "id": 13,
            "title": "Glaces",
            "slug": "Glaces",
            "createdAt": "2021-03-24T01:46:51+01:00",
            "updatedAt": null,
            "picture": "..\/assets\/images\/products\/categorie-glace"
        },
        ...
    ]
    ```
 
* **Error Response:**

  * **Code:** 404 Category Not Found <br />
    **Content:** 
    ```json
    {
        "status": 404,
        "message": "Category not found"
    }

**Browse Products**
----
  Browse all products from a specified category
  
* **Access to**
  
  all
  
* **URL**

  /categories/{id}

* **Method:**

  `GET`

* **Data Params**

   none

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    ```json
    [
        {
            "id": 81,
            "name": "Magnum Vanille",
            "description": "Ea provident fugit illum quasi.",
            "picture": "..\/assets\/images\/products\/magnum-vanille",
            "price": "7.00",
            "availability": false,
            "slug": "Magnum-Vanille",
            "createdAt": "2021-03-24T01:46:51+01:00",
            "updatedAt": null,
            "category": {
                "id": 13,
                "title": "Glaces",
                "slug": "Glaces",
                "createdAt": "2021-03-24T01:46:51+01:00",
                "updatedAt": null,
                "picture": "..\/assets\/images\/products\/categorie-glace"
            }
        },
        ...
    ]
    ```
 
* **Error Response:**

  * **Code:** 404 Category Not Found <br />
    **Content:** 
    ```json
    {
        "status": 404,
        "message": "Category not found"
    }
    ```

**Read Product**
----
  Read a specified product
  
* **Access to**
  
  all
  
* **URL**

  /products/{id}

* **Method:**

  `GET`

* **Data Params**

   none

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    ```json
    {
        "id": 81,
        "name": "Magnum Vanille",
        "description": "Ea provident fugit illum quasi.",
        "picture": "..\/assets\/images\/products\/magnum-vanille",
        "price": "7.00",
        "availability": false,
        "slug": "Magnum-Vanille",
        "createdAt": "2021-03-24T01:46:51+01:00",
        "updatedAt": null,
        "category": {
            "id": 13,
            "title": "Glaces",
            "slug": "Glaces",
            "createdAt": "2021-03-24T01:46:51+01:00",
            "updatedAt": null,
            "picture": "..\/assets\/images\/products\/categorie-glace",
            "__initializer__": null,
            "__cloner__": null,
            "__isInitialized__": true
        }
    }
    ```
 
* **Error Response:**

  * **Code:** 404 Product Not Found <br />
    **Content:** 
    ```json
    {
        "status": 404,
        "message": "Product not found"
    }
    ```

**Browse DeliveryPoints**
----
  Browse all Delivery Points
  
* **Access to**
  
  all
  
* **URL**

  /delivery-points

* **Method:**

  `GET`

* **Data Params**

   none

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    ```json
    {
        ...
        }
    }
    ```
 
* **Error Response:**

  * **Code:** 404 ... Not Found <br />
    **Content:** 
    ```json
    {
        "status": 404,
        "message": "... not found"
    }
    ```

**Post Orders**
----
  Send an order
  
* **Access to**
  
  Logged users
  
* **URL**

  /orders

* **Method:**

  `POST`

* **Data Params**

   **required**

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    ```json
    {
        ... TODO
    }
    ```
 
* **Error Response:**

  * **Code:** 404 Product Not Found <br />
    **Content:** 
    ```json
    {
        ... TODO
    }

**Browse Orders**
----
  Browse all orders from a logged user
  
* **Access to**
  
  Logged users
  
* **URL**

  /orders

* **Method:**

  `GET`

* **Data Params**

   none

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    ```json
    {
        ... TODO
    }
    ```
 
* **Error Response:**

  * **Code:** 404 Product Not Found <br />
    **Content:** 
    ```json
    {
        ... TODO
    }
    ```

**Read Orders**
----
  Read a specified orders from a logged user
  
* **Access to**
  
  Logged users
  
* **URL**

  /orders/{id}

* **Method:**

  `GET`

* **Data Params**

   none

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    ```json
    {
        ... TODO
    }
    ```
 
* **Error Response:**

  * **Code:** 404 Product Not Found <br />
    **Content:** 
    ```json
    {
        ... TODO
    }
    ```