# PHP no-framework back-end

This's a simple and extendable back-end for an e-commerce website made with Vanilla PHP, a custom-made Router and relational database, and a dynamic way of adding new product types and new type properties through API endpoints.  

## Installation

Use the command `php -S localhost:8080` to run the service once the apache server is running and the Mysql one as well. 


## About
This project was a test task for the junior PHP developer position at [Scandiweb](https://scandiweb.com/).
As per the instructions, the project was written with vanilla PHP, custom `Router`, uses OOP principles to implement the application logic, which was used to implement the Custom `QueryBuilder`. 

The application serves the `index.php` file, which defines the routing rules, methods, and middleware. 

![image](https://user-images.githubusercontent.com/37253065/175048086-a0a7d6ac-31af-4d5f-a484-dcbd0a7942e9.png)



## Additional features

## Additional features
The requirements state that the application should have **3 product types: Book, DVD, Furniture** but must use only **one API endpoint** to add any product type. The application provides additional end points for 

**Get All Product Types:** `http://your-domain/getAllProductTypes`,

**Add Product Type:** `http://your-domain/addProductType`,

**Get All Type Properties:** `http://your-domain/getAllTypeProperties`,

**Add Type Property:** `http://your-domain/addTypeProperty`,

 and finally **Get Application Data:** `http://your-domain/getApplicationData`,  which serves and array of all the **product types, and their properties**, to be later used to construct **dropdown menu items** and the **type property input fields** when rendering the application UI.




![image](https://user-images.githubusercontent.com/37253065/175050914-46c9436d-971e-4f08-848f-e8f8e47763a9.png)
