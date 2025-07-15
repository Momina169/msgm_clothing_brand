# MSGM Clothing E-commerce Project

##  How to Run the Project Locally

Follow these steps to set up and run the project on your local machine:

1. Download the code from gitHub, first replace ".env.example" file to ".env" file.
2. Start **Apache** and **MySQL** services using the **XAMPP Control Panel**.
3. Open the project folder named `msgm`.
4. Inside the `msgm` folder, open the Command Prompt (CMD).
5. Run the following command to migrate and seed the database:

   ```bash
   php artisan migrate:fresh --seed
6. Start the Laravel development server by running:
    ```bash
    php artisan serve
7. Visit the application in your browser:
    ```bash
    http://127.0.0.1:8000
    
## How to Access the Admin Panel

1. Open your browser and navigate to:
   ```bash
   http://127.0.0.1:8000/admin/dashboard
2. You will see the Admin Login Page. Use the following credentials to log in:
        Email: devmomina@gmail.com
        Password: password

3. After logging in, you will be redirected to the Admin Dashboard.


