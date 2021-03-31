**Table of contents**
===============
- [**Table of contents**](#table-of-contents)
- [**Usage**](#Usage)
  - [**Accessing the API**](#accessing-the-API)
  - [**Image path**](#image-path)
- [**Routes**](#api)
  - [**Login**](#login)
  - [**Registering**](#registering)
  - [**Delete User**](#delete-user)
  - [**Browse Categories**](#browse-categories)
  - [**Browse Products**](#browse-products)
  - [**Read Product**](#read-product)
  - [**Browse DeliveryPoints**](#browse-deliverypoints)
  - [**Post Orders**](#post-orders)
  - [**Browse Orders**](#browse-orders)
  - [**Read Orders**](#read-orders)

**Usage**
===============

**Accessing the API**
----
Server Base URI (HTTPS) : https://back.onthespot.link/

Server Base URI (HTTP) : http://back.onthespot.link/

API Base URI (HTTPS) : https://api.onthespot.link/api

API Base URI (HTTP) : http://api.onthespot.link/api

**Image path**
----
For example, an image could be at : https://onthespot.apotheoz.tech/back/public/assets/images/beignet-choconoisette.png

Here, the `picture` property is `assets/images/beignet-choconoisette.png`

The picture property is a relative path from the Server Base URI

So you should concatenate from the Server Base URI to have the absolute URL of the picture to display.

**Routes**
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

   **required:**
   ```json
   {
     "deliveryTime": "2021-03-22T18:10:07+01:00",
     "deliveryPoint": 154,
     "orderProducts": [
       {
          "product": 141,
          "quantity": 3
       },
       {
          "product": 150,
          "quantity": 7
       }
     ]
   }
   ```

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    ```json
    {
      "deliveryTime": "2021-03-22T18:10:07+01:00",
      "status": 0,
      "user": 2,
      "deliveryPoint": 12,
      "createdAt": "2021-03-22T18:10:07+01:00",
      "updatedAt": null,
      "orderProducts": [
        {
        "product": 5,
        "quantity": 3
        },
        {
        "product": 15,
        "quantity": 7
        }
      ]
    }
    ```
 
* **Error Response:**

  * **Code:** 404 Product Not Found <br />
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
    [
      {
        "id": 51,
        "deliveryTime": "2021-03-22T18:10:07+01:00",
        "status": 0
      },
      {
        "id": 52,
        "deliveryTime": "2021-03-22T18:10:07+01:00",
        "status": 0
      }
    ]
    ```
 
* **Error Response:**

  * **Code:** 404 Product Not Found <br />
    **Content:** 
    ```json
    {
      "code": 401,
      "message": "Credentials invalid."
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
      "id": 53,
      "deliveryTime": "2021-03-22T18:10:07+01:00",
      "status": 0,
      "createdAt": "2021-03-22T18:10:07+01:00",
      "orderProducts": [
        {
          "quantity": 3,
          "product": {
            "name": "Cornet Chocolat",
            "price": "5.00"
          }
        },
        {
          "quantity": 7,
          "product": {
            "name": "Orangina 33cL",
            "price": "2.00"
          }
        }
      ],
      "deliveryPoint": {
        "name": "Plage de l'Estacade",
        "description": "Asperiores maiores commodi ut est qui nulla.",
        "location": "-82.348974, 37.062855",
        "city": {
          "name": "Cap Breton"
        }
      }
    }
    ```
 
* **Error Response:**

  * **Code:** 404 Product Not Found <br />
    **Content:** 
    ```json
    {
      "status": 404,
      "error": "Commande non trouvée."
    }
    ```
