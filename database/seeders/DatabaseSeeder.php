<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Division;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Branches
        $branches = [
            ['name' => 'Cabang Jakarta', 'code' => 'JKT', 'address' => 'Jl. Sudirman No. 12, Jakarta'],
            ['name' => 'Cabang Surabaya', 'code' => 'SBY', 'address' => 'Jl. Basuki Rahmat No. 45, Surabaya'],
            ['name' => 'Cabang Bandung', 'code' => 'BDG', 'address' => 'Jl. Dago No. 100, Bandung'],
        ];

        $branchModels = [];
        foreach ($branches as $branch) {
            $branchModels[] = Branch::create($branch);
        }

        // 2. Seed Divisions
        $divisions = ['IT Department', 'Human Resources', 'Finance & Accounting', 'Operational'];
        $divisionModels = [];
        foreach ($divisions as $division) {
            $divisionModels[] = Division::create(['name' => $division]);
        }

        // 3. Seed Categories
        $categories = [
            ['name' => 'Elektronik & Gadget', 'code' => 'ELEK'],
            ['name' => 'Alat Tulis Kantor', 'code' => 'ATK'],
            ['name' => 'Mebel & Furniture', 'code' => 'MBL'],
        ];
        $categoryModels = [];
        foreach ($categories as $category) {
            $categoryModels[] = Category::create($category);
        }

        // 4. Seed Users
        // Super Admin (Bisa lihat semua cabang)
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@inventory.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'branch_id' => null,
            'division_id' => null,
        ]);

        // Admin Jakarta
        User::create([
            'name' => 'Admin Jakarta',
            'email' => 'admin.jkt@inventory.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'branch_id' => $branchModels[0]->id,
            'division_id' => null,
        ]);

        // Admin Surabaya
        User::create([
            'name' => 'Admin Surabaya',
            'email' => 'admin.sby@inventory.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'branch_id' => $branchModels[1]->id,
            'division_id' => null,
        ]);

        // User Jakarta
        User::create([
            'name' => 'User JKT (IT)',
            'email' => 'user.jkt@inventory.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'branch_id' => $branchModels[0]->id,
            'division_id' => $divisionModels[0]->id, // IT
        ]);

        // User Surabaya
        User::create([
            'name' => 'User SBY (Finance)',
            'email' => 'user.sby@inventory.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'branch_id' => $branchModels[1]->id,
            'division_id' => $divisionModels[2]->id, // Finance
        ]);

        // 5. Seed Products (Barcodes generated automatically in Product booted method)
        // Products for Jakarta
        Product::create([
            'name' => 'Laptop ASUS ROG',
            'category_id' => $categoryModels[0]->id, // ELEK
            'branch_id' => $branchModels[0]->id, // Jakarta
            'description' => 'Laptop Gaming untuk kebutuhan rendering berat',
            'stock' => 10,
        ]);

        Product::create([
            'name' => 'Printer HP LaserJet',
            'category_id' => $categoryModels[0]->id,
            'branch_id' => $branchModels[0]->id,
            'description' => 'Printer hitam putih kecepatan tinggi',
            'stock' => 5,
        ]);

        Product::create([
            'name' => 'Meja Kantor Kayu Jati',
            'category_id' => $categoryModels[2]->id, // MBL
            'branch_id' => $branchModels[0]->id,
            'description' => 'Meja direktur dengan laci ganda',
            'stock' => 8,
        ]);

        // Products for Surabaya
        Product::create([
            'name' => 'PC Desktop Lenovo Core i7',
            'category_id' => $categoryModels[0]->id,
            'branch_id' => $branchModels[1]->id, // Surabaya
            'description' => 'Komputer kantor standar administrasi',
            'stock' => 12,
        ]);

        Product::create([
            'name' => 'Buku Catatan A5',
            'category_id' => $categoryModels[1]->id, // ATK
            'branch_id' => $branchModels[1]->id,
            'description' => 'Buku catatan bersampul kulit keras',
            'stock' => 100,
        ]);

        // Products for Bandung
        Product::create([
            'name' => 'Kursi Ergonomis Kantor',
            'category_id' => $categoryModels[2]->id,
            'branch_id' => $branchModels[2]->id, // Bandung
            'description' => 'Kursi jaring dengan sandaran kepala',
            'stock' => 15,
        ]);
    }
}
