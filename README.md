# Nitroxy retail system

A Point of Sale (POS) system run in a browser. It handles a barcodescanner to sell stuffs.

## State of the project

The project is in active use but should be considerd a beta.

## Features
* Sell things with a barcode scanner
* Type the name of a product to not need the scanner
* Track deliveries
* Track stock
* Contains a (very) limited book keeping system

# Installation

## Requirements

* php >= 5.3
* php-gd (for bar code generation and statistics display)
* php-curl (used by login service which might want to be replaced)
* php-mysql
* MySQL
* gnu-barcode (deb package barcode)

## Development

1. `vagrant up`
2. Point your browser to `http://localhost:3010`
3. Login with `admin:admin`.

## Production

See (old) `readme.markdown`, essentially just configure your webserver, import database and go.
