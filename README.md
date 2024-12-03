# Rest Client
[![Coverage Status](https://coveralls.io/repos/github/REST-PHP/Client/badge.svg?branch=main)](https://coveralls.io/github/REST-PHP/Client?branch=main)

This library is a lightweight, flexible PHP client for making REST API requests. It simplifies HTTP request handling by offering a builder-pattern interface for constructing requests and converting responses. This client supports all common HTTP methods and adheres to PSR standards, ensuring compatibility with existing PHP libraries and frameworks.

## Installation
Coming Soon

## Features
- PSR-7 and PSR-18 Compatible: Leverages PSR interfaces for requests, responses, and HTTP client integration.
- Builder Pattern: Use the RestRequestBuilder to construct requests intuitively.
- Supports All HTTP Methods: Includes GET, POST, PUT, DELETE, PATCH, HEAD, OPTIONS, and TRACE.
- Customizable Configuration: Includes authentication and other configuration options via RestClientConfiguration.
- Boolean Query Parameters: Handles boolean query parameters, converting them to string representations as needed.
