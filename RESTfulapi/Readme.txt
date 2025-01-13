This completes the basic setup for a RESTful API using PHP and MySQL. The API supports CRUD operations for a "user" resource. Remember to replace `localhost` with your actual server address when testing.


POST http://localhost/RESTfulapi/user
Content-Type: application/json

{
    "name": "ravi",
    "email": "ravi@example.com"
}

GET http://localhost/RESTfulapi/user

GET http://localhost/RESTfulapi/user?id=1

PUT http://localhost/RESTfulapi/user
Content-Type: application/json

{
    "id": 1,
    "name": "ravi Updated",
    "email": "ravi.updated@example.com"
}

DELETE http://localhost/RESTfulapi/user
Content-Type: application/json

{
    "id": 1
}



