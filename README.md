url-task API Usage Instructions
----------------------------

### Setup

1.  Clone the project to your computer:

    ```
    git clone {repository-url}
    cd {repository-folder}

    ```

2.  Start the development server:

    ```
    php artisan serve

    ```

3.  Install dependencies using Composer:

    ```
    composer install

    ```

4.  Create a `.env` file by copying `.env.example` and configuring it for your environment:

    ```
    cp .env.example .env
    php artisan key:generate

    ```

5.  Start using the API with tools like Postman, cURL, or any HTTP client.

* * * * *

### API Endpoints

#### 1\. **Encode a URL**

-   **Endpoint**: `POST http://{your-url}/api/encode`
-   **Headers**:
    -   `Content-Type: application/json`
-   **Body (Raw JSON)**:

    ```
    {
        "url": "https://www.thisisalongdomain.com/with/some/parameters?and=here_too"
    }

    ```

-   **Response**: A shortened URL:

    ```
    {
        "short_url": "http://{your-url}/{short-code}"
    }

    ```

#### 2\. **Decode a Short URL**

-   **Endpoint**: `POST http://{your-url}/api/decode`
-   **Headers**:
    -   `Content-Type: application/json`
-   **Body (Raw JSON)**:

    ```
    {
        "short_url": "http://{your-url}/{short-code}"
    }

    ```

-   **Response**: The original long URL:

    ```
    {
        "original_url": "https://www.thisisalongdomain.com/with/some/parameters?and=here_too"
    }

    ```

* * * * *

### Tests

1.  Run the tests from UrlShortenerTest if you wish

    ```
    php artisan test

    ```

* * * * *

### Notes
-   The system stores the mapping between short and long URLs in the cache for **2 hours**.
-   After 2 hours, the data will be lost unless persistent storage is implemented.
