<?php

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class ProductPageCest
{
    // tests
    public function pageLoads(AcceptanceTester $I)
    {
        # Act
        $I->amOnPage('/product?sku=driscolls-strawberries');

        # Assert the correct title is set on the page
        $I->seeInTitle('Driscoll’s Strawberries');

        # Assert the existence of certain text on the page
        $I->see('Driscoll’s Strawberries');

        # Assert the existence of a certain element on the page
        $I->seeElement('.product-thumb');

        # Assert the existence of text within a specific element on the page
        $I->see('$4.99', '.product-price');
    }


    public function reviewAProductTest(AcceptanceTester $I)
    {
        $I->amOnPage('/product?sku=driscolls-strawberries');

        $name = 'Bob';
        $I->fillField('[test=reviewer-name-input]', $name);

        $review = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus in pulvinar libero. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus in pulvinar libero. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.';
        $I->fillField('[test=review-textarea]', $review);

        $I->click('[test=review-submit-button]');

        $I->seeElement('[test=review-confirmation]');

        // added to see review on page
        $I->see($name, '[test=review-name]');

        $I->see($review, '[test=review-content]');
    }

    # bad url by user for a product
    public function ProductNotFoundTest(AcceptanceTester $I)
    {
        $badUrl = 'I-am-a-bad-url';
        $I->amOnPage('/product?sku=' . $badUrl);

        // added to see review on page
        $I->see('Sorry unable to find');
    }

    # test a review
    public function newReviewError(AcceptanceTester $I)
    {
        # Act
        $I->amOnPage('/product?sku=driscolls-strawberries');

        # No name entered
        $name = '';
        $I->fillField('[test=reviewer-name-input]', $name);

        $review = 'Lorem ipsum dolor sit amet';
        $I->fillField('[test=review-textarea]', $review);

        $I->click('[test=review-submit-button]');

        // error element should appear
        $I->seeElement('[test=review-error]');
    }

    # all products
    public function allProductsTest(AcceptanceTester $I)
    {
        # Act
        $I->amOnPage('/products');

        $productCount = count($I->grabMultiple('.product-link'));
        $I->assertGreaterThanOrEqual(10, $productCount);
    }

    # new product
    public function newProductsTest(AcceptanceTester $I)
    {
        # Act
        $I->amOnPage('/products');

        $productCount = count($I->grabMultiple('.product-link'));


        # Act
        $I->amOnPage('/products/new');
        $newProduct = [
            'name' => 'name',
            'sku' => 'sku-sku',
            'description' => 'description is required',
            'price' => 4.0,
            'available' => 10,
            'weight' => 125.5,
            'perishable' => '1',
        ];

        $I->fillField('[test=name]', $newProduct['name']);
        $I->click('[test=newproduct-submit-button]');
        $I->seeElement('[test=product-added-error]');

        $I->fillField('[test=name]', $newProduct['name']);

        $I->fillField('[test=sku]', $newProduct['sku']);
        $I->fillField('[test=description]', $newProduct['description']);
        $I->fillField('[test=price]', $newProduct['price']);
        $I->fillField('[test=available]', $newProduct['available']);
        $I->fillField('[test=weight]', $newProduct['weight']);
        $I->checkOption('[test=perishable]');

        $I->click('[test=newproduct-submit-button]');
        $I->seeElement('[test=product-added-confirmation]');


        $I->amOnPage('/products');

        $productCountAfter = count($I->grabMultiple('.product-link'));
        $I->assertGreaterThan($productCount, $productCountAfter);
    }
}