<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Setting;

class TiffinShopSeeder extends Seeder
{
    public function run(): void
    {
        // Shop settings
        Setting::set('shop_name', 'Sri Lakshmi Tiffin Centre');
        Setting::set('shop_phone', '+91 82473 12751');
        Setting::set('shop_address', 'MG Road, Vijayawada, Andhra Pradesh');
        Setting::set('shop_timings', 'Mon-Sat: 7AM - 10PM, Sun: 8AM - 9PM');
        Setting::set('whatsapp_number', '+91 82473 12751');

        // Categories
        $breakfast = Category::create(['name' => 'Breakfast', 'icon' => '🌅', 'sort_order' => 1]);
        $lunch     = Category::create(['name' => 'Lunch', 'icon' => '🍱', 'sort_order' => 2]);
        $snacks    = Category::create(['name' => 'Snacks', 'icon' => '🍿', 'sort_order' => 3]);
        $drinks    = Category::create(['name' => 'Drinks', 'icon' => '🥤', 'sort_order' => 4]);

        // Breakfast items
        MenuItem::create(['category_id' => $breakfast->id, 'name' => 'Idli (2 pcs)', 'description' => 'Soft steamed rice cakes with sambar and chutney', 'price' => 30, 'is_veg' => true, 'is_special' => true]);
        MenuItem::create(['category_id' => $breakfast->id, 'name' => 'Masala Dosa', 'description' => 'Crispy dosa with spiced potato filling', 'price' => 60, 'is_veg' => true, 'is_special' => true]);
        MenuItem::create(['category_id' => $breakfast->id, 'name' => 'Vada (2 pcs)', 'description' => 'Crispy medu vada with sambar', 'price' => 40, 'is_veg' => true]);
        MenuItem::create(['category_id' => $breakfast->id, 'name' => 'Upma', 'description' => 'Semolina cooked with vegetables', 'price' => 35, 'is_veg' => true]);
        MenuItem::create(['category_id' => $breakfast->id, 'name' => 'Pongal', 'description' => 'Rice and lentil porridge with ghee', 'price' => 40, 'is_veg' => true]);

        // Lunch items
        MenuItem::create(['category_id' => $lunch->id, 'name' => 'Meals (Full)', 'description' => 'Rice, dal, sambar, rasam, 2 curries, papad, pickle', 'price' => 100, 'is_veg' => true, 'is_special' => true]);
        MenuItem::create(['category_id' => $lunch->id, 'name' => 'Chapati (3 pcs)', 'description' => 'Soft wheat chapati with curry', 'price' => 50, 'is_veg' => true]);
        MenuItem::create(['category_id' => $lunch->id, 'name' => 'Egg Rice', 'description' => 'Fried rice with scrambled egg', 'price' => 80, 'is_veg' => false]);
        MenuItem::create(['category_id' => $lunch->id, 'name' => 'Chicken Curry Rice', 'description' => 'Spicy chicken curry with steamed rice', 'price' => 130, 'is_veg' => false]);

        // Snacks
        MenuItem::create(['category_id' => $snacks->id, 'name' => 'Samosa (2 pcs)', 'description' => 'Crispy pastry with spiced potato filling', 'price' => 20, 'is_veg' => true]);
        MenuItem::create(['category_id' => $snacks->id, 'name' => 'Bajji (4 pcs)', 'description' => 'Banana pepper fritters', 'price' => 30, 'is_veg' => true]);
        MenuItem::create(['category_id' => $snacks->id, 'name' => 'Bread Omelette', 'description' => 'Egg omelette with toasted bread', 'price' => 50, 'is_veg' => false]);

        // Drinks
        MenuItem::create(['category_id' => $drinks->id, 'name' => 'Filter Coffee', 'description' => 'Traditional South Indian filter coffee', 'price' => 25, 'is_veg' => true, 'is_special' => true]);
        MenuItem::create(['category_id' => $drinks->id, 'name' => 'Masala Tea', 'description' => 'Spiced ginger tea', 'price' => 20, 'is_veg' => true]);
        MenuItem::create(['category_id' => $drinks->id, 'name' => 'Lassi', 'description' => 'Sweet or salted yogurt drink', 'price' => 40, 'is_veg' => true]);
        MenuItem::create(['category_id' => $drinks->id, 'name' => 'Fresh Lime Soda', 'description' => 'Lime with soda, sweet or salted', 'price' => 30, 'is_veg' => true]);
    }
}