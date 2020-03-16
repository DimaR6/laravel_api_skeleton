<?php namespace Tests\Repositories;

use App\Models\ProductToCategory;
use App\Repositories\ProductToCategoryRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ProductToCategoryRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ProductToCategoryRepository
     */
    protected $productToCategoryRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->productToCategoryRepo = \App::make(ProductToCategoryRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_product_to_category()
    {
        $productToCategory = factory(ProductToCategory::class)->make()->toArray();

        $createdProductToCategory = $this->productToCategoryRepo->create($productToCategory);

        $createdProductToCategory = $createdProductToCategory->toArray();
        $this->assertArrayHasKey('id', $createdProductToCategory);
        $this->assertNotNull($createdProductToCategory['id'], 'Created ProductToCategory must have id specified');
        $this->assertNotNull(ProductToCategory::find($createdProductToCategory['id']), 'ProductToCategory with given id must be in DB');
        $this->assertModelData($productToCategory, $createdProductToCategory);
    }

    /**
     * @test read
     */
    public function test_read_product_to_category()
    {
        $productToCategory = factory(ProductToCategory::class)->create();

        $dbProductToCategory = $this->productToCategoryRepo->find($productToCategory->id);

        $dbProductToCategory = $dbProductToCategory->toArray();
        $this->assertModelData($productToCategory->toArray(), $dbProductToCategory);
    }

    /**
     * @test update
     */
    public function test_update_product_to_category()
    {
        $productToCategory = factory(ProductToCategory::class)->create();
        $fakeProductToCategory = factory(ProductToCategory::class)->make()->toArray();

        $updatedProductToCategory = $this->productToCategoryRepo->update($fakeProductToCategory, $productToCategory->id);

        $this->assertModelData($fakeProductToCategory, $updatedProductToCategory->toArray());
        $dbProductToCategory = $this->productToCategoryRepo->find($productToCategory->id);
        $this->assertModelData($fakeProductToCategory, $dbProductToCategory->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_product_to_category()
    {
        $productToCategory = factory(ProductToCategory::class)->create();

        $resp = $this->productToCategoryRepo->delete($productToCategory->id);

        $this->assertTrue($resp);
        $this->assertNull(ProductToCategory::find($productToCategory->id), 'ProductToCategory should not exist in DB');
    }
}
