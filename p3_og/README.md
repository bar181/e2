

# Project 3
+ By: Bradley Ross
+ URL: <http://e2p3.bradross.me>

## Game planning
+ Create an array of two options, heads and tails.
+ Randomly choose one of these options from the array and assign it as Player A's 


## Graduate requirement
+ [x] I have integrated testing into my application
+ [ ] I am taking this course for undergraduate credit and have opted out of integrating testing into my application

## Outside resources
*your list of outside resources go here*

## Notes for instructor
*any notes for me to refer to while grading; if none, omit this section*

### Codeception testing output

```
Codeception PHP Testing Framework v4.1.22
Powered by PHPUnit 9.5.10 by Sebastian Bergmann and contributors.

Acceptance Tests (7) ---------------------------------------------------------------------------------------------------------
NewProductPageCest: Test adding new product
Signature: NewProductPageCest:testAddingNewProduct
Test: tests/acceptance/NewProductPageCest.php:testAddingNewProduct
Scenario --
 I am on page "/products/new"
 I fill field "[test=name-input]","tenetur aut sed"
 I fill field "[test=sku-input]","tenetur-aut-sed"
 I fill field "[test=description-input]","Minus architecto officia aliquid sunt."
 I fill field "[test=price-input]",4.99
 I fill field "[test=available-input]",50
 I fill field "[test=weight-input]",1.34
 I click "[test=submit-button]"
 I am on page "/product?sku=tenetur-aut-sed"
 I see "tenetur aut sed"
 PASSED 

NewProductPageCest: Test validation is working
Signature: NewProductPageCest:testValidationIsWorking
Test: tests/acceptance/NewProductPageCest.php:testValidationIsWorking
Scenario --
 I am on page "/products/new"
 I click "[test=submit-button]"
 I see element "[test=validation-errors-alert]"
 PASSED 

ProductPageCest: Page loads
Signature: ProductPageCest:pageLoads
Test: tests/acceptance/ProductPageCest.php:pageLoads
Scenario --
 I am on page "/product?sku=driscolls-strawberries"
 I see in title "Driscoll’s Strawberries"
 I see "Driscoll’s Strawberries"
 I see element ".product-thumb"
 I see "$4.99","[test=product-price]"
 PASSED 

ProductPageCest: Review a product test
Signature: ProductPageCest:reviewAProductTest
Test: tests/acceptance/ProductPageCest.php:reviewAProductTest
Scenario --
 I am on page "/product?sku=driscolls-strawberries"
 I fill field "[test=reviewer-name-input]","Bob"
 I fill field "[test=review-textarea]","Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus in pulvinar liber..."
 I click "[test=review-submit-button]"
 I see element "[test=review-confirmation]"
 I see "Bob","[test=review-name]"
 I see "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus in pulvinar libero. Pelle...","[test=review-content]"
 PASSED 

ProductPageCest: Review validation test
Signature: ProductPageCest:reviewValidationTest
Test: tests/acceptance/ProductPageCest.php:reviewValidationTest
Scenario --
 I am on page "/product?sku=driscolls-strawberries"
 I fill field "[test=reviewer-name-input]","Bob"
 I fill field "[test=review-textarea]","This review is not long enough"
 I click "[test=review-submit-button]"
 I see "The value for review must be at least 200 character(s) long"
 PASSED 

ProductPageCest: Product not found
Signature: ProductPageCest:productNotFound
Test: tests/acceptance/ProductPageCest.php:productNotFound
Scenario --
 I am on page "/product?sku=abc"
 I see element "[test=product-not-found-header]"
 PASSED 

ProductsPageCest: Page loads
Signature: ProductsPageCest:pageLoads
Test: tests/acceptance/ProductsPageCest.php:pageLoads
Scenario --
 I am on page "/products"
 I see element "[test=all-products-header]"
 I grab multiple ".product-link"
 I assert greater than or equal 10,20
 PASSED 