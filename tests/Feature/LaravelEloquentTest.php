<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Person;
use App\Models\Product;
use App\Models\Scopes\IsActiveScope;
use App\Models\Voucher;
use App\Models\Wallet;
use Carbon\Carbon;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CommentSeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\ImageSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\ReviewSeeder;
use Database\Seeders\TagSeeder;
use Database\Seeders\VirtualAccountSeeder;
use Database\Seeders\VoucherSeeder;
use Database\Seeders\WalletSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class LaravelEloquentTest extends TestCase
{
    public function testInsert(){
        $category = new Category();
        $category->id = 'GADGET';
        $category->name = 'Gadget';
        $result = $category->save();

        $this->assertTrue($result);
    }
    public function testInsertManyCategories(){
        $categories = [];
        for ($i = 0; $i < 100; $i++){
            $categories[] = [
                'id' => 'ID-'.$i,
                'name' => 'Name-'.$i,
            ];
        }
        $result = Category::query()->insert($categories);

        $this->assertTrue($result);

        $total = Category::query()->count();
        $this->assertEquals(100, $total);
    }
    public function testFind(){
        $this->seed(CategorySeeder::class);

        $result = Category::query()->find('GADGET');
        $this->assertNotNull($result);
        $this->assertEquals('GADGET', $result->id);
        $this->assertEquals('GADGET', $result->name);
        $this->assertEquals('GADGET Category', $result->description);
    }
    public function testSave(){
        $this->seed(CategorySeeder::class);

        $category = Category::query()->find('GADGET');

        $category->name = 'GADGET AJA';
        $result = $category->update();

        $this->assertTrue($result);
    }
    public function testSelect(){
        for ($i = 0; $i < 100; $i++){
            $category = new Category();
            $category->id = 'ID-'.$i;
            $category->name = 'Name-'.$i;
            $category->save();
        }

        $result = Category::query()->whereNull('description')->get();
        $this->assertCount(100, $result);
        $result->each(function($query){
            $this->assertNull($query->description);
        });
    }
    public function testSelectUpdate(){
        for ($i = 0; $i < 100; $i++){
            $category = new Category();
            $category->id = 'ID-'.$i;
            $category->name = 'Name-'.$i;
            $category->save();
        }

        $category = Category::query()->whereNull('description')->get();
        $this->assertCount(100, $category);
        $category->each(function($query){
            $query->description = 'Description';
            $this->assertTrue($query->save());
        });
    }
    public function testUpdateMany(){
        $categories = [];
        for ($i = 0; $i < 100; $i++){
            $categories[] = [
                'id' => 'ID-'.$i,
                'name' => 'Name-'.$i,
            ];
        }
        $result = Category::query()->insert($categories);
        $this->assertTrue($result);

        Category::query()->where('description', null)->update(['description' => 'Description']);

        $total = Category::query()->where('description', 'Description')->count();
        $this->assertEquals(100, $total);
    }
    public function testDelete(){
        $this->seed(CategorySeeder::class);

        $category = Category::query()->find('GADGET');
        $result = $category->delete();

        $this->assertTrue($result);

        $total = Category::query()->count();
        $this->assertEquals(0, $total);
    }
    public function testDeleteMany(){
        $categories = [];
        for ($i = 0; $i < 100; $i++){
            $categories[] = [
                'id' => 'ID-'.$i,
                'name' => 'Name-'.$i,
            ];
        }
        $result = Category::query()->insert($categories);
        $this->assertTrue($result);

        Category::query()->whereNull('description')->delete();

        $total = Category::query()->count();
        $this->assertEquals(0, $total);
    }
    public function testUUID(){
        $voucher = new Voucher();
        $voucher->name = 'GRATIS ONGKIR';
        $voucher->voucher_code = 'GRATIS-ONGKIR';
        $voucher->save();

        $this->assertNotNull($voucher->id);
    }
    public function testCreateComment(){
        $this->seed([CategorySeeder::class, ProductSeeder::class, VoucherSeeder::class, CommentSeeder::class]);
        $product = Product::query()->first();   
        $comment = new Comment();
        $comment->email = 'Tb9rE@example.com';
        $comment->title = 'Test Title';
        $comment->comment = 'Test Comment';
        $comment->created_at = Carbon::now();
        $comment->updated_at = Carbon::now();
        $comment->commentable_id = $product->id;
        $comment->commentable_type = Product::class;
        $comment->save();

        $this->assertNotNull($comment->id);
    }
    public function testDefaultAttributeValues(){
        $this->seed([CategorySeeder::class, ProductSeeder::class, VoucherSeeder::class, CommentSeeder::class]);
        $product = Product::query()->first();
        $comment = new Comment();
        $comment->email = 'Tb9rE@example.com';
        $comment->created_at = Carbon::now();
        $comment->updated_at = Carbon::now();
        $comment->commentable_id = $product->id;
        $comment->commentable_type = Product::class;
        $comment->save();

        $this->assertNotNull($comment->id);
        $this->assertEquals('Sample title', $comment->title);
        $this->assertEquals('Sample comment', $comment->comment);
    }
    public function testCreate(){
        $request = [
            'id' => 'FOOD',
            'name' => 'Opor Cumi Tahu',
            'description' => 'Enak bet njir'
        ];

        $category = new Category($request);
        $category->save();

        $this->assertNotNull($category->id);
    }
    public function testCreateUsingQueryBuilder(){
        $request = [
            'id' => 'FOOD',
            'name' => 'Opor Cumi Tahu',
            'description' => 'Enak bet njir'
        ];

        $category = Category::query()->create($request);

        $this->assertNotNull($category->id);
    }
    public function testUpdate(){
        $this->seed(CategorySeeder::class);
        $request = [
            'name' => 'GADGET',
            'description' => 'GADGET Category'
        ];

        $category = Category::query()->find('GADGET');
        $category->fill($request);
        $category->save();

        $this->assertEquals('GADGET', $category->name);
        $this->assertEquals('GADGET Category', $category->description);
    }
    public function testCreateVoucherUUID(){
        $voucher = new Voucher();
        $voucher->name = 'GRATIS ONGKIR';
        $voucher->save();

        $this->assertNotNull($voucher->id);
        $this->assertNotNull($voucher->voucher_code);
    }
    public function testSoftDelete(){
        $this->seed(VoucherSeeder::class);

        $voucher = Voucher::query()->where('name', 'Sample Voucher')->first();
        $voucher->delete();

        $voucher = Voucher::query()->where('name', 'Sample Voucher')->first();
        $this->assertNull($voucher);
    }
    public function testSoftDeleteTrashed(){
        $this->seed(VoucherSeeder::class);

        $voucher = Voucher::query()->where('name', 'Sample Voucher')->first();
        $voucher->delete();

        $voucher = Voucher::query()->where('name', 'Sample Voucher')->first();
        $this->assertNull($voucher);

        $voucher = Voucher::withTrashed()->where('name', 'Sample Voucher')->first();
        $this->assertNotNull($voucher);
    }
    public function testRemoveGlobalScope(){
        $category = new Category();
        $category->id = 'FOOD';
        $category->name = 'Food';
        $category->description = 'Food Category';
        $category->is_active = false;
        $category->save();

        $result = Category::query()->find('FOOD');
        $this->assertNull($result);
    }
    public function testRemoveGlobalScopeWithout(){
        $category = new Category();
        $category->id = 'FOOD';
        $category->name = 'Food';
        $category->description = 'Food Category';
        $category->is_active = false;
        $category->save();

        $result = Category::query()->find('FOOD');
        $this->assertNull($result);

        $result = Category::query()->withoutGlobalScopes([IsActiveScope::class])->find('FOOD');
        $this->assertNotNull($result);
    }
    public function testLocalScope(){
        $voucher = new Voucher();
        $voucher->name = 'GRATIS ONGKIR';
        $voucher->is_active = true;
        $voucher->save();

        $total = Voucher::query()->active()->count();
        $this->assertEquals(1, $total);

        $total = Voucher::query()->nonActive()->count();
        $this->assertEquals(0, $total);
    }
    public function testQueryOneToOne(){
        $this->seed([CustomerSeeder::class, WalletSeeder::class]);

        $customer = Customer::query()->find('EARL');
        $this->assertNotNull($customer);

        $wallet = $customer->wallet;
        $this->assertNotNull($wallet);
        $this->assertEquals(1000000000, $wallet->amount);
    }
    public function testQueryOneToMany(){
        $this->seed(CategorySeeder::class);
        $this->seed(ProductSeeder::class);

        $category = Category::query()->find('GADGET');
        $this->assertNotNull($category);
        $products = $category->products;
        $this->assertNotNull($products);
        $this->assertCount(2, $products);
    }
    public function testQueryOneToManyProduct(){
        $this->seed(CategorySeeder::class);
        $this->seed(ProductSeeder::class);

        $product = Product::query()->find('GADGET-1');
        $this->assertNotNull($product);
        $category = $product->category;
        $this->assertNotNull($category);
        $this->assertEquals('GADGET', $category->name);
    }
    public function testInsertRelationshipOneToOne(){
        $customer = new Customer();
        $customer->id = 'EARL';
        $customer->name = 'Earl Dev';
        $customer->email = 'qBQ0U@example.com';
        $this->assertTrue($customer->save());
        $this->assertNotNull($customer);

        $wallet = new Wallet();
        $wallet->amount = 150000000;
        $customer->wallet()->save($wallet);

        $this->assertNotNull($wallet);

    }
    public function testInsertRelationshipOneToMany(){
        $category = new Category();
        $category->id = 'GADGET';
        $category->name = 'Gadget';
        $category->description = 'Gadget Category';
        $category->save();

        $this->assertNotNull($category);

        $product = new Product();
        $product->id = 'GADGET-1';
        $product->name = 'Gadget 1';
        $product->description = 'Gadget 1 Description';
        $category->products()->save($product);

        $this->assertNotNull($product);
    }
    public function testSearchProduct(){
        $this->testInsertRelationshipOneToMany();
        $category = Category::query()->find('GADGET');
        $outOfStock = $category->products()->where('quantity', '<', 1)->get();

        $this->assertCount(1, $outOfStock);
        $this->assertNotNull($outOfStock);
    }
    public function testHasOneOfMany(){
        $this->seed(CategorySeeder::class);
        $this->seed(ProductSeeder::class);

        $category = Category::query()->find('GADGET');
        $this->assertNotNull($category);

        $cheapestProduct = $category->cheapestProduct;
        $this->assertNotNull($cheapestProduct);
        $this->assertEquals('GADGET-1', $cheapestProduct->id);

        $mostExpensiveProduct = $category->mostExpensiveProduct;
        $this->assertNotNull($mostExpensiveProduct);
        $this->assertEquals('GADGET-2', $mostExpensiveProduct->id);
    }
    public function testHasOneThrough(){
        $this->seed([CustomerSeeder::class, WalletSeeder::class, VirtualAccountSeeder::class]);

        $customer = Customer::query()->find('EARL');
        $this->assertNotNull($customer);

        $virtualAccount = $customer->virtualAccount;
        $this->assertNotNull($virtualAccount);
        $this->assertEquals('BCA', $virtualAccount->bank);
    }
    public function testHasManyThrough(){
        $this->seed([CustomerSeeder::class, CategorySeeder::class, ProductSeeder::class, ReviewSeeder::class]);

        $category = Category::query()->find('GADGET');
        $category->save();

        $this->assertNotNull($category);

        $review = $category->review;
        $this->assertNotNull($review);
        $this->assertCount(2, $review);
    }
    public function testInsertManyToMany(){
        $this->seed([CustomerSeeder::class, CategorySeeder::class, ProductSeeder::class]);

        $customer = Customer::query()->find('EARL');
        $this->assertNotNull($customer);

        $customer->likeProducts()->attach('GADGET-1');

        $products = $customer->likeProducts;
        $this->assertCount(1, $products);
        $this->assertEquals('GADGET-1', $products[0]->id);
    }
    public function testRemoveManyToMany(){
        $this->seed([CustomerSeeder::class, CategorySeeder::class, ProductSeeder::class]);

        $customer = Customer::query()->find('EARL');
        $this->assertNotNull($customer);

        $customer->likeProducts()->detach('GADGET-1');

        $products = $customer->likeProducts;
        $this->assertNotNull($products);
        $this->assertCount(0, $products);
    }
    public function testPivotAttribute(){
        $this->testInsertManyToMany();
        $customer = Customer::query()->find('EARL');
        $products = $customer->likeProducts;
    
        foreach($products as $product){
            $pivot = $product->pivot;
            $this->assertNotNull($pivot);
            $this->assertNotNull($pivot->customer_id);
            $this->assertNotNull($pivot->product_id);
            $this->assertNotNull($pivot->created_at);
        }
    }
    public function testPivotAttributeCondition(){
        $this->testInsertManyToMany();
        $customer = Customer::query()->find('EARL');
        $products = $customer->likeProductsLastWeek;
    
        foreach($products as $product){
            $pivot = $product->pivot;
            $this->assertNotNull($pivot);
            $this->assertNotNull($pivot->customer_id);
            $this->assertNotNull($pivot->product_id);
            $this->assertNotNull($pivot->created_at);
        }
    }
    public function testPivotModel(){
        $this->testInsertManyToMany();

        $customer = Customer::query()->find('EARL');
        $products = $customer->likeProducts;

        foreach($products as $product){
            $pivot = $product->pivot;
            $this->assertNotNull($pivot);

            $customer = $pivot->customer;
            $this->assertNotNull($customer);

            $product = $pivot->product;
            $this->assertNotNull($product);
        }
    }
    public function testOneToOnePolymorphicCustomer(){
        $this->seed([CustomerSeeder::class, ImageSeeder::class]);

        $customer = Customer::query()->find('EARl');
        $this->assertNotNull($customer);

        $image = $customer->image;
        $this->assertNotNull($image);
        $this->assertEquals('https://www.google.com/images/branding/googlelogo/2x/googlelogo_color_120x44dp.png', $image->url);
    }
    public function testOneToOnePolymorphicProduct(){
        $this->seed([CustomerSeeder::class, CategorySeeder::class, ProductSeeder::class, ImageSeeder::class]);

        $product = Product::query()->find('GADGET-1');
        $this->assertNotNull($product);

        $image = $product->image;
        $this->assertNotNull($image);

        $this->assertEquals('https://www.google.com/images/branding/googlelogo/2x/googlelogo_color_120x44dp.png', $image->url);
    }
    public function testOneToManyPolymorphicProduct(){
        $this->seed([CategorySeeder::class, ProductSeeder::class, VoucherSeeder::class, CommentSeeder::class]);

        $product = Product::query()->find('GADGET-1');
        $comments = $product->comment;
        $this->assertNotNull($comments);
        foreach($comments as $comment){
            $this->assertEquals('Tb9rE@example.com', $comment->email);
            $this->assertEquals('Test Title', $comment->title);
            $this->assertEquals('Test Comment', $comment->comment);
        }
    }
    public function testOneOfManyPolymorphic(){
        $this->seed([CategorySeeder::class, ProductSeeder::class, VoucherSeeder::class, CommentSeeder::class]);

        $product = Product::query()->find('GADGET-1');
        $latestComment = $product->latestComment;
        $this->assertNotNull($latestComment);

        $oldestComment = $product->oldestComment;
        $this->assertNotNull($oldestComment);
    }
    public function testManyToManyPolymorphic(){
        $this->seed([CategorySeeder::class, ProductSeeder::class, VoucherSeeder::class, TagSeeder::class]);

        $product = Product::query()->find('GADGET-1');
        $tags = $product->tags;
        $this->assertNotNull($tags);
        $this->assertCount(1, $tags);

        foreach($tags as $tag){
            $this->assertNotNull($tag);
            $this->assertNotNull($tag->id);
            $this->assertNotNull($tag->name);
        }
    }
    public function testEagerLoading(){
        $this->seed([CustomerSeeder::class, WalletSeeder::class, ImageSeeder::class]);
        $customer = Customer::query()->with(['wallet', 'image'])->find('EARL');
        $this->assertNotNull($customer);
    }
    public function testEagerLoadingInModel(){
        $this->seed([CustomerSeeder::class, WalletSeeder::class, ImageSeeder::class]);
        $customer = Customer::query()->find('EARL');
        $this->assertNotNull($customer);
    }
    public function testQueryRelations(){
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $category = Category::query()->find('GADGET');
        $product = $category->products()->where('price', '=', 1000)->get();
        $this->assertNotNull($product);
        $this->assertCount(1, $product);
    }
    public function testQueryRelationsAggregate(){
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $category = Category::query()->find('GADGET');
        $totalProduct = $category->products()->count();

        $this->assertEquals(2, $totalProduct);
    }
    public function testEloquentCollection(){
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $products = Product::query()->get();
        $this->assertCount(2, $products);

        $products = $products->toQuery()->where('price', '=', 1000)->get();
        $this->assertCount(1, $products);
    }
    // public function testPerson()
    // {
    //     $person = new Person();
    //     $person->first_name = "Earl";
    //     $person->last_name = "Dev";
    //     $person->save();

    //     self::assertEquals("Earl Dev", $person->full_name);

    //     $person->full_name = "Earl Phantomhive";
    //     $person->save();

    //     self::assertEquals("Earl", $person->first_name);
    //     self::assertEquals("Phantomhive", $person->last_name);
    // }
    public function testPerson()
    {
        $person = new Person();
        $person->first_name = "Earl";
        $person->last_name = "Dev";
        $person->save();

        self::assertEquals("EARL Dev", $person->full_name);

        $person->full_name = "Ciel Phantomhive";
        $person->save();

        self::assertEquals("CIEL", $person->first_name);
        self::assertEquals("Phantomhive", $person->last_name);
    }
    public function testAttributeCasting(){
        $person = new Person();
        $person->first_name = "Earl";
        $person->last_name = "Dev";
        $person->save();

        $this->assertNotNull($person->created_at);
        $this->assertNotNull($person->updated_at);
        $this->assertInstanceOf(Carbon::class, $person->created_at);
        $this->assertInstanceOf(Carbon::class, $person->updated_at);
    }
    public function testCustomCasts(){
        $person = new Person();
        $person->first_name = 'Earl';
        $person->last_name = 'Dev';
        $person->address = new Address('Jl. Kebon Jeruk', 'Jakarta', 'DKI Jakarta', 'Indonesia', '12345');
        $person->save();

        $person = Person::query()->find($person->id);
        $this->assertInstanceOf(Address::class, $person->address);
        $this->assertNotNull($person->address);
        $this->assertEquals('Jl. Kebon Jeruk', $person->address->street);
        $this->assertEquals('Jakarta', $person->address->city);
        $this->assertEquals('DKI Jakarta', $person->address->state);
        $this->assertEquals('Indonesia', $person->address->country);
        $this->assertEquals('12345', $person->address->postal_code);
    }
    public function testSerialization(){
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $products = Product::query()->get();
        $this->assertCount(2, $products);

        $json = $products->toJson(JSON_PRETTY_PRINT);
        Log::info($json);
    }
    public function testSerializationToArray(){
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $products = Product::query()->get();
        $this->assertCount(2, $products);

        $array = $products->toArray();
        Log::info($array);
    }
    public function testSerializationRelation(){
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $products = Product::query()->get();
        $products->load('category');
        $this->assertCount(2, $products);

        $json = $products->toJson(JSON_PRETTY_PRINT);
        Log::info($json);
    }
    public function testFactory(){
        $employee1 = Employee::factory()->programmer()->create([
            'id' => 1,
            'name' => 'Earl Dev'
        ]);
        $this->assertNotNull($employee1);

        $employee2 = Employee::factory()->seniorProgrammer()->create([
            'id' => 2,
            'name' => 'Ciel Phantomhive'
        ]);
        $this->assertNotNull($employee2);
    }
}
